<?php

/**
 * Pure-PHP X.509 Parser
 *
 * PHP version 5
 *
 * Encode and decode X.509 certificates.
 *
 * The extensions are from {@link http://tools.ietf.org/html/rfc5280 RFC5280} and
 * {@link http://web.archive.org/web/19961027104704/http://www3.netscape.com/eng/security/cert-exts.html Netscape Certificate Extensions}.
 *
 * Note that loading an X.509 certificate and resaving it may invalidate the signature.  The reason being that the signature is based on a
 * portion of the certificate that contains optional parameters with default values.  ie. if the parameter isn't there the default value is
 * used.  Problem is, if the parameter is there and it just so happens to have the default value there are two ways that that parameter can
 * be encoded.  It can be encoded explicitly or left out all together.  This would effect the signature value and thus may invalidate the
 * the certificate all together unless the certificate is re-signed.
 *
 * @category  File
 * @package   X509
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2012 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */

namespace phpseclib\File;

use phpseclib\Crypt\Hash;
use phpseclib\Crypt\Random;
use phpseclib\Crypt\RSA;
use phpseclib\File\ASN1\Element;
use phpseclib\Math\BigInteger;
use DateTime;
use DateTimeZone;

/**
 * Pure-PHP X.509 Parser
 *
 * @package X509
 * @author  Jim Wigginton <terrafrost@php.net>
 * @access  public
 */
class X509
{
    /**
     * Flag to only accept signatures signed by certificate authorities
     *
     * Not really used anymore but retained all the same to suppress E_NOTICEs from old installs
     *
     * @access public
     */
    const VALIDATE_SIGNATURE_BY_CA = 1;

    /**#@+
     * @access public
     * @see \phpseclib\File\X509::getDN()
    */
    /**
     * Return internal array representation
     */
    const DN_ARRAY = 0;
    /**
     * Return string
     */
    const DN_STRING = 1;
    /**
     * Return ASN.1 name string
     */
    const DN_ASN1 = 2;
    /**
     * Return OpenSSL compatible array
     */
    const DN_OPENSSL = 3;
    /**
     * Return canonical ASN.1 RDNs string
     */
    const DN_CANON = 4;
    /**
     * Return name hash for file indexing
     */
    const DN_HASH = 5;
    /**#@-*/

    /**#@+
     * @access public
     * @see \phpseclib\File\X509::saveX509()
     * @see \phpseclib\File\X509::saveCSR()
     * @see \phpseclib\File\X509::saveCRL()
    */
    /**
     * Save as PEM
     *
     * ie. a base64-encoded PEM with a header and a footer
     */
    const FORMAT_PEM = 0;
    /**
     * Save as DER
     */
    const FORMAT_DER = 1;
    /**
     * Save as a SPKAC
     *
     * Only works on CSRs. Not currently supported.
     */
    const FORMAT_SPKAC = 2;
    /**
     * Auto-detect the format
     *
     * Used only by the load*() functions
     */
    const FORMAT_AUTO_DETECT = 3;
    /**#@-*/

    /**
     * Attribute value disposition.
     * If disposition is >= 0, this is the index of the target value.
     */
    const ATTR_ALL = -1; // All attribute values (array).
    const ATTR_APPEND = -2; // Add a value.
    const ATTR_REPLACE = -3; // Clear first, then add a value.

    /**
     * ASN.1 syntax for X.509 certificates
     *
     * @var array
     * @access private
     */
    var $Certificate;

    /**#@+
     * ASN.1 syntax for various extensions
     *
     * @access private
     */
    var $DirectoryString;
    var $PKCS9String;
    var $AttributeValue;
    var $Extensions;
    var $KeyUsage;
    var $ExtKeyUsageSyntax;
    var $BasicConstraints;
    var $KeyIdentifier;
    var $CRLDistributionPoints;
    var $AuthorityKeyIdentifier;
    var $CertificatePolicies;
    var $AuthorityInfoAccessSyntax;
    var $SubjectAltName;
    var $SubjectDirectoryAttributes;
    var $PrivateKeyUsagePeriod;
    var $IssuerAltName;
    var $PolicyMappings;
    var $NameConstraints;

    var $CPSuri;
    var $UserNotice;

    var $netscape_cert_type;
    var $netscape_comment;
    var $netscape_ca_policy_url;

    var $Name;
    var $RelativeDistinguishedName;
    var $CRLNumber;
    var $CRLReason;
    var $IssuingDistributionPoint;
    var $InvalidityDate;
    var $CertificateIssuer;
    var $HoldInstructionCode;
    var $SignedPublicKeyAndChallenge;
    /**#@-*/

    /**#@+
     * ASN.1 syntax for various DN attributes
     *
     * @access private
     */
    var $PostalAddress;
    /**#@-*/

    /**
     * ASN.1 syntax for Certificate Signing Requests (RFC2986)
     *
     * @var array
     * @access private
     */
    var $CertificationRequest;

    /**
     * ASN.1 syntax for Certificate Revocation Lists (RFC5280)
     *
     * @var array
     * @access private
     */
    var $CertificateList;

    /**
     * Distinguished Name
     *
     * @var array
     * @access private
     */
    var $dn;

    /**
     * Public key
     *
     * @var string
     * @access private
     */
    var $publicKey;

    /**
     * Private key
     *
     * @var string
     * @access private
     */
    var $privateKey;

    /**
     * Object identifiers for X.509 certificates
     *
     * @var array
     * @access private
     * @link http://en.wikipedia.org/wiki/Object_identifier
     */
    var $oids;

    /**
     * The certificate authorities
     *
     * @var array
     * @access private
     */
    var $CAs;

    /**
     * The currently loaded certificate
     *
     * @var array
     * @access private
     */
    var $currentCert;

    /**
     * The signature subject
     *
     * There's no guarantee \phpseclib\File\X509 is going to re-encode an X.509 cert in the same way it was originally
     * encoded so we take save the portion of the original cert that the signature would have made for.
     *
     * @var string
     * @access private
     */
    var $signatureSubject;

    /**
     * Certificate Start Date
     *
     * @var string
     * @access private
     */
    var $startDate;

    /**
     * Certificate End Date
     *
     * @var string
     * @access private
     */
    var $endDate;

    /**
     * Serial Number
     *
     * @var string
     * @access private
     */
    var $serialNumber;

    /**
     * Key Identifier
     *
     * See {@link http://tools.ietf.org/html/rfc5280#section-4.2.1.1 RFC5280#section-4.2.1.1} and
     * {@link http://tools.ietf.org/html/rfc5280#section-4.2.1.2 RFC5280#section-4.2.1.2}.
     *
     * @var string
     * @access private
     */
    var $currentKeyIdentifier;

    /**
     * CA Flag
     *
     * @var bool
     * @access private
     */
    var $caFlag = false;

    /**
     * SPKAC Challenge
     *
     * @var string
     * @access private
     */
    var $challenge;

    /**
     * Default Constructor.
     *
     * @return \phpseclib\File\X509
     * @access public
     */
    function __construct()
    {
        // Explicitly Tagged Module, 1988 Syntax
        // http://tools.ietf.org/html/rfc5280#appendix-A.1

        $this->DirectoryString = array(
            'type'     => ASN1::TYPE_CHOICE,
            'children' => array(
                'teletexString'   => array('type' => ASN1::TYPE_TELETEX_STRING),
                'printableString' => array('type' => ASN1::TYPE_PRINTABLE_STRING),
                'universalString' => array('type' => ASN1::TYPE_UNIVERSAL_STRING),
                'utf8String'      => array('type' => ASN1::TYPE_UTF8_STRING),
                'bmpString'       => array('type' => ASN1::TYPE_BMP_STRING)
            )
        );

        $this->PKCS9String = array(
            'type'     => ASN1::TYPE_CHOICE,
            'children' => array(
                'ia5String'       => array('type' => ASN1::TYPE_IA5_STRING),
                'directoryString' => $this->DirectoryString
            )
        );

        $this->AttributeValue = array('type' => ASN1::TYPE_ANY);

        $AttributeType = array('type' => ASN1::TYPE_OBJECT_IDENTIFIER);

        $AttributeTypeAndValue = array(
            'type'     => ASN1::TYPE_SEQUENCE,
            'children' => array(
                'type' => $AttributeType,
                'value'=> 