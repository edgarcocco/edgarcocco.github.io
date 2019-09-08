<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	session_start();
	require_once '../Constants/getPath.php';
	require_once '../Constants/header.php';
	require_once '../PHPScripts/ipinfo.php';
	require_once '../PHPScripts/functions.php';
	require_once "GoogleLogin.php";
	require('../Constants/phpmailerAuth.php');


	if(isset($_SESSION['access_token']) || isset($_SESSION['user_id'])){
		$home_url = $rootUrl;
		header('Location: ' . $home_url);
		return;
	}

	$loginURL = $gClient->createAuthUrl();

	$countryCode = ip_info("Visitor", "Country Code");
	$error_msg = "";

	$success = false;
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(isset($_POST['submit']))
	{
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$password = mysqli_real_escape_string($dbc, trim($_POST['password']));
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
		$phonenumber = mysqli_real_escape_string($dbc, trim($_POST['phonenumber']));
		
		$birth_month = $_POST['birth_month'] + 1;
		$birth_day = $_POST['birth_day'];
		$birth_year = $_POST['birth_year'];
		$birthdate = $birth_year .'-' . $birth_month . '-' . $birth_day;
		
		$country = $_POST['countrySelect'];

		if(empty($username))
		{
			$error_msg = "No username specified";
		}
		else if(str_contain_space($username))
		{
			$error_msg = "Username can't contain spaces";
		}
		else if(empty($password))
		{
			$error_msg = "No password specified";
		}
		else if(empty($email))
		{
			$error_msg = "No email specified";
		}
		else if(empty($first_name))
		{
			$error_msg = "No first name specified";
		}
		else{
			if(!userNameExists($username))
			{
				if(!emailExists($email))
				{
					$query = "INSERT INTO " . TABLE_NAME . " (username, password, email, first_name, last_name, phone_number, birthdate, country, join_date)" .
						"VALUES ('$username', SHA('$password'), '$email', '$first_name', '$last_name', '$phonenumber', '$birthdate', '$country', NOW())";

					mysqli_query($dbc, $query);

					$user_id = getUserId($email);
					if($user_id >= 0){
						$query = "INSERT INTO user_plan (user_id, plan_expiration, current_plan) " .
							"VALUES ('$user_id', DATE_ADD(NOW(), INTERVAL 14 DAY), 'trial')";
						mysqli_query($dbc,$query);
					}
					$confirm_code = uniqid();
					$query = "INSERT INTO confirmed_accounts(user_id, confirm_code, is_confirmed) " .
						"VALUES ('$user_id', '$confirm_code', 0)";
					mysqli_query($dbc,$query);

					$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
					try {
						//Server settings
						$mail->SMTPDebug = 0;                                 // Enable verbose debug output
						$mail->isSMTP();                                      // Set mailer to use SMTP
						$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
						$mail->SMTPAuth = true;                               // Enable SMTP authentication
						$mail->Username = 'rolle.cocco.enterprises@gmail.com';                 // SMTP username
						$mail->Password = 'Danjito1';                           // SMTP password
						$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
						$mail->Port = 587;                                    // TCP port to connect to

						//Recipients
						$mail->setFrom('rolle.cocco.enterprises@gmail.com', 'STAPP');
						$mail->addAddress($email, 'user');     // Add a recipient

						$body = '<a href="'.$rootUrl.'account/confirm.php?id='.$user_id.'&code='.$confirm_code.'">Click this link to confirm your account with STAPP!</a>';

						//Content
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->Subject = 'New STAPP Account Confirmation!';
						$mail->Body    = $body;
						$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						if (!$mail->send()) {
							$mail->ErrorInfo;
						}
					} catch (Exception $e) {
					}


					$success = true;
					mysqli_close($dbc);
				}
				else
				{
					$error_msg = "Email is already in use!";
				}
			}
			else
			{
				$error_msg = "Username is already in use!";
			}
		}
	}
?>
<link rel="stylesheet" href="../css/signup.css">
<link href="../css/buttons-si.css" rel="stylesheet">
</head>
<body>
    <div class="navigation-bg border-bottom box-shadow">
        <?php require_once('../Constants/navigation.php'); ?>
    </div>

    <div class="signupContent">
        <h2 class="text-center">Create a STAPP Personal Account.</h2>
		<?php if(!empty($error_msg)) printf('<div class="alert alert-danger mb-0" role="alert">%s</div>', $error_msg); ?>
		<?php if($success == true) echo '<div class="alert alert-success mb-0" role="alert">Account Created successfully you are ready to <a href="login.php">log-in</a></div>'; ?>
	<div class="d-flex justify-content-between my-3">
	<form class="form-signup col-md-6" method="post" action="">
			<label for="username">Username</label>
			<div class="input-group mb-3">
                <!--<div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">@</span>
                </div>-->
                <input type="text" placeholder="Username" id="username" name="username" class="form-control"
                    aria-describedby="basic-addon1" aria-label="Username" />
            </div>

            <label for="email">Email Address</label>
            <div class="input-group mb-3">
				<!--<div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Email</span>
                </div>-->
                <input type="text" placeholder="email@example.com" id="email" name="email" class="form-control" /> 
            </div>

			<label for="password">Password</label>
			<div class="input-group mb-3">
				<!--<div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">#</span>
                </div>-->
                <input type="password" placeholder="••••••••" id="password" name="password" class="form-control" />
            </div>
			<hr />
			<div class="row">
				<div class="col-sm-6">
					<label for="firstname">First Name</label>
					<div class="input-group mb-2">
						<!--<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Email</span>
						</div>-->
						<input type="text" placeholder="First Name" id="firstname" name="firstname" class="form-control" /> 
					</div>
				</div>

				<div class="col-sm-6">
					<label for="lastname">Last Name</label>
					<div class="input-group mb-2">
						<!--<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Email</span>
						</div>-->
						<input type="text" placeholder="Last Name" id="lastname" name="lastname" class="form-control" /> 
					</div>
				</div>
			</div>
			
			<label for="phonenumber">Phone Number</label>
			<div class="input-group mb-2">
				<input type="text" placeholder="###-###-####" id="phonenumber" name="phonenumber" class="form-control" /> 
			</div>
			

			<label>Birthday Date</label>
			<table class="" style="width:100%">
                <tr>
                    <td width="40%">
                    <select id="selectMonth" class="form-control mb-2 p-1" name="birth_month"><select>
                    </td>
                    <td width="30%">
                    <select id="selectDay" class="form-control mb-2 p-1" name="birth_day"></select>
                    </td>
                    <td width="30%">
                    <select id="selectYear" class="form-control mb-2 p-1" name="birth_year"></select>
                    </td>
                </tr>
			</table>

			<label for="countrySelect">Country</label>
			<div class="input-group mb-2">
				<select class="form-control mb-2 p-1" name="countrySelect" id="countrySelect">
					<option value="AF">Afghanistan</option>
					<option value="AX">Åland Islands</option>
					<option value="AL">Albania</option>
					<option value="DZ">Algeria</option>
					<option value="AS">American Samoa</option>
					<option value="AD">Andorra</option>
					<option value="AO">Angola</option>
					<option value="AI">Anguilla</option>
					<option value="AQ">Antarctica</option>
					<option value="AG">Antigua and Barbuda</option>
					<option value="AR">Argentina</option>
					<option value="AM">Armenia</option>
					<option value="AW">Aruba</option>
					<option value="AU">Australia</option>
					<option value="AT">Austria</option>
					<option value="AZ">Azerbaijan</option>
					<option value="BS">Bahamas</option>
					<option value="BH">Bahrain</option>
					<option value="BD">Bangladesh</option>
					<option value="BB">Barbados</option>
					<option value="BY">Belarus</option>
					<option value="BE">Belgium</option>
					<option value="BZ">Belize</option>
					<option value="BJ">Benin</option>
					<option value="BM">Bermuda</option>
					<option value="BT">Bhutan</option>
					<option value="BO">Bolivia, Plurinational State of</option>
					<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
					<option value="BA">Bosnia and Herzegovina</option>
					<option value="BW">Botswana</option>
					<option value="BV">Bouvet Island</option>
					<option value="BR">Brazil</option>
					<option value="IO">British Indian Ocean Territory</option>
					<option value="BN">Brunei Darussalam</option>
					<option value="BG">Bulgaria</option>
					<option value="BF">Burkina Faso</option>
					<option value="BI">Burundi</option>
					<option value="KH">Cambodia</option>
					<option value="CM">Cameroon</option>
					<option value="CA">Canada</option>
					<option value="CV">Cape Verde</option>
					<option value="KY">Cayman Islands</option>
					<option value="CF">Central African Republic</option>
					<option value="TD">Chad</option>
					<option value="CL">Chile</option>
					<option value="CN">China</option>
					<option value="CX">Christmas Island</option>
					<option value="CC">Cocos (Keeling) Islands</option>
					<option value="CO">Colombia</option>
					<option value="KM">Comoros</option>
					<option value="CG">Congo</option>
					<option value="CD">Congo, the Democratic Republic of the</option>
					<option value="CK">Cook Islands</option>
					<option value="CR">Costa Rica</option>
					<option value="CI">Côte d'Ivoire</option>
					<option value="HR">Croatia</option>
					<option value="CU">Cuba</option>
					<option value="CW">Curaçao</option>
					<option value="CY">Cyprus</option>
					<option value="CZ">Czech Republic</option>
					<option value="DK">Denmark</option>
					<option value="DJ">Djibouti</option>
					<option value="DM">Dominica</option>
					<option value="DO">Dominican Republic</option>
					<option value="EC">Ecuador</option>
					<option value="EG">Egypt</option>
					<option value="SV">El Salvador</option>
					<option value="GQ">Equatorial Guinea</option>
					<option value="ER">Eritrea</option>
					<option value="EE">Estonia</option>
					<option value="ET">Ethiopia</option>
					<option value="FK">Falkland Islands (Malvinas)</option>
					<option value="FO">Faroe Islands</option>
					<option value="FJ">Fiji</option>
					<option value="FI">Finland</option>
					<option value="FR">France</option>
					<option value="GF">French Guiana</option>
					<option value="PF">French Polynesia</option>
					<option value="TF">French Southern Territories</option>
					<option value="GA">Gabon</option>
					<option value="GM">Gambia</option>
					<option value="GE">Georgia</option>
					<option value="DE">Germany</option>
					<option value="GH">Ghana</option>
					<option value="GI">Gibraltar</option>
					<option value="GR">Greece</option>
					<option value="GL">Greenland</option>
					<option value="GD">Grenada</option>
					<option value="GP">Guadeloupe</option>
					<option value="GU">Guam</option>
					<option value="GT">Guatemala</option>
					<option value="GG">Guernsey</option>
					<option value="GN">Guinea</option>
					<option value="GW">Guinea-Bissau</option>
					<option value="GY">Guyana</option>
					<option value="HT">Haiti</option>
					<option value="HM">Heard Island and McDonald Islands</option>
					<option value="VA">Holy See (Vatican City State)</option>
					<option value="HN">Honduras</option>
					<option value="HK">Hong Kong</option>
					<option value="HU">Hungary</option>
					<option value="IS">Iceland</option>
					<option value="IN">India</option>
					<option value="ID">Indonesia</option>
					<option value="IR">Iran, Islamic Republic of</option>
					<option value="IQ">Iraq</option>
					<option value="IE">Ireland</option>
					<option value="IM">Isle of Man</option>
					<option value="IL">Israel</option>
					<option value="IT">Italy</option>
					<option value="JM">Jamaica</option>
					<option value="JP">Japan</option>
					<option value="JE">Jersey</option>
					<option value="JO">Jordan</option>
					<option value="KZ">Kazakhstan</option>
					<option value="KE">Kenya</option>
					<option value="KI">Kiribati</option>
					<option value="KP">Korea, Democratic People's Republic of</option>
					<option value="KR">Korea, Republic of</option>
					<option value="KW">Kuwait</option>
					<option value="KG">Kyrgyzstan</option>
					<option value="LA">Lao People's Democratic Republic</option>
					<option value="LV">Latvia</option>
					<option value="LB">Lebanon</option>
					<option value="LS">Lesotho</option>
					<option value="LR">Liberia</option>
					<option value="LY">Libya</option>
					<option value="LI">Liechtenstein</option>
					<option value="LT">Lithuania</option>
					<option value="LU">Luxembourg</option>
					<option value="MO">Macao</option>
					<option value="MK">Macedonia, the former Yugoslav Republic of</option>
					<option value="MG">Madagascar</option>
					<option value="MW">Malawi</option>
					<option value="MY">Malaysia</option>
					<option value="MV">Maldives</option>
					<option value="ML">Mali</option>
					<option value="MT">Malta</option>
					<option value="MH">Marshall Islands</option>
					<option value="MQ">Martinique</option>
					<option value="MR">Mauritania</option>
					<option value="MU">Mauritius</option>
					<option value="YT">Mayotte</option>
					<option value="MX">Mexico</option>
					<option value="FM">Micronesia, Federated States of</option>
					<option value="MD">Moldova, Republic of</option>
					<option value="MC">Monaco</option>
					<option value="MN">Mongolia</option>
					<option value="ME">Montenegro</option>
					<option value="MS">Montserrat</option>
					<option value="MA">Morocco</option>
					<option value="MZ">Mozambique</option>
					<option value="MM">Myanmar</option>
					<option value="NA">Namibia</option>
					<option value="NR">Nauru</option>
					<option value="NP">Nepal</option>
					<option value="NL">Netherlands</option>
					<option value="NC">New Caledonia</option>
					<option value="NZ">New Zealand</option>
					<option value="NI">Nicaragua</option>
					<option value="NE">Niger</option>
					<option value="NG">Nigeria</option>
					<option value="NU">Niue</option>
					<option value="NF">Norfolk Island</option>
					<option value="MP">Northern Mariana Islands</option>
					<option value="NO">Norway</option>
					<option value="OM">Oman</option>
					<option value="PK">Pakistan</option>
					<option value="PW">Palau</option>
					<option value="PS">Palestinian Territory, Occupied</option>
					<option value="PA">Panama</option>
					<option value="PG">Papua New Guinea</option>
					<option value="PY">Paraguay</option>
					<option value="PE">Peru</option>
					<option value="PH">Philippines</option>
					<option value="PN">Pitcairn</option>
					<option value="PL">Poland</option>
					<option value="PT">Portugal</option>
					<option value="PR">Puerto Rico</option>
					<option value="QA">Qatar</option>
					<option value="RE">Réunion</option>
					<option value="RO">Romania</option>
					<option value="RU">Russian Federation</option>
					<option value="RW">Rwanda</option>
					<option value="BL">Saint Barthélemy</option>
					<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
					<option value="KN">Saint Kitts and Nevis</option>
					<option value="LC">Saint Lucia</option>
					<option value="MF">Saint Martin (French part)</option>
					<option value="PM">Saint Pierre and Miquelon</option>
					<option value="VC">Saint Vincent and the Grenadines</option>
					<option value="WS">Samoa</option>
					<option value="SM">San Marino</option>
					<option value="ST">Sao Tome and Principe</option>
					<option value="SA">Saudi Arabia</option>
					<option value="SN">Senegal</option>
					<option value="RS">Serbia</option>
					<option value="SC">Seychelles</option>
					<option value="SL">Sierra Leone</option>
					<option value="SG">Singapore</option>
					<option value="SX">Sint Maarten (Dutch part)</option>
					<option value="SK">Slovakia</option>
					<option value="SI">Slovenia</option>
					<option value="SB">Solomon Islands</option>
					<option value="SO">Somalia</option>
					<option value="ZA">South Africa</option>
					<option value="GS">South Georgia and the South Sandwich Islands</option>
					<option value="SS">South Sudan</option>
					<option value="ES">Spain</option>
					<option value="LK">Sri Lanka</option>
					<option value="SD">Sudan</option>
					<option value="SR">Suriname</option>
					<option value="SJ">Svalbard and Jan Mayen</option>
					<option value="SZ">Swaziland</option>
					<option value="SE">Sweden</option>
					<option value="CH">Switzerland</option>
					<option value="SY">Syrian Arab Republic</option>
					<option value="TW">Taiwan, Province of China</option>
					<option value="TJ">Tajikistan</option>
					<option value="TZ">Tanzania, United Republic of</option>
					<option value="TH">Thailand</option>
					<option value="TL">Timor-Leste</option>
					<option value="TG">Togo</option>
					<option value="TK">Tokelau</option>
					<option value="TO">Tonga</option>
					<option value="TT">Trinidad and Tobago</option>
					<option value="TN">Tunisia</option>
					<option value="TR">Turkey</option>
					<option value="TM">Turkmenistan</option>
					<option value="TC">Turks and Caicos Islands</option>
					<option value="TV">Tuvalu</option>
					<option value="UG">Uganda</option>
					<option value="UA">Ukraine</option>
					<option value="AE">United Arab Emirates</option>
					<option value="GB">United Kingdom</option>
					<option value="US">United States</option>
					<option value="UM">United States Minor Outlying Islands</option>
					<option value="UY">Uruguay</option>
					<option value="UZ">Uzbekistan</option>
					<option value="VU">Vanuatu</option>
					<option value="VE">Venezuela, Bolivarian Republic of</option>
					<option value="VN">Viet Nam</option>
					<option value="VG">Virgin Islands, British</option>
					<option value="VI">Virgin Islands, U.S.</option>
					<option value="WF">Wallis and Futuna</option>
					<option value="EH">Western Sahara</option>
					<option value="YE">Yemen</option>
					<option value="ZM">Zambia</option>
					<option value="ZW">Zimbabwe</option>
				</select>
			</div>
			<button class="btn btn-outline-success btn-block" type="submit" name="submit">Create Account </button>
			<small>
				You will receive a 14 days trial upon a successful sign up.
			</small>
			<br />
			<small>
				By clicking on "Create Account", you are agreeing to the <a href="../policies/terms">Terms of Service</a> and the <a href="../policies/privacy_policy">Privacy Policy</a>
			</small>

        </form>
		<div class="col my-auto mx-3">
			<div class="">
				<p class="lead">Or</p>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row justify-content-center">
				<div class="col-lg my-5" align="center">
					<?php if(isset($_GET['error']) && $_GET['error'] == 1){ ?>
					<div class="alert alert-danger pb-0">
						<p>Email has been already registered!</p>
					</div>
					<?php }?>
					<button class="btn-si btn-google" onclick="window.location = '<?php echo $loginURL ?>';">Sign in using Google</button>
				</div>
			</div>
		</div>
	</div>
	</div>

    <script src="../scripts/dateHandler.js"></script>
	<script src="../scripts/functions.js" type="text/javascript">
		var countryEl = document.getElementById('countrySelect');
		var country_Code = "<?php Print($countryCode); ?>";
		console.log(country_Code);
		setOption(countryEl, country_Code);
	</script>

		<?php require_once('../Constants/fullfooter.php'); ?>
</body>
</html>

