<div class="isfixed">
	<footer class="py-3" style="background-color: #222222; margin: 0 auto;">
		<div class="container">
			<div class="row">
				<div class="col-2">
				  <p><img src="<?php printf("%simages/transparent_stapp.png", $return_path);?>" width="64" height="64" /></p>
				  <p><small class="text-muted">&copy; <?php echo date("Y"); ?></small></p>
				</div>
				<div class="col-5 col-md-5 col-lg-6 align-self-center">
					<a class="text-muted mx-2" href="<?php printf("%spolicies/privacy_policy", $return_path);?>">Privacy</a>
					<a class="text-muted mx-2" href="<?php printf("%spolicies/terms", $return_path);?>">Terms</a>
					<a class="text-muted mx-2" href="<?php printf("%shelp/support", $return_path);?>">Support</a>
					<a class="text-white mx-2" href="mailto:rolle.cocco.enterprises.com">Contact Us</a>
				</div>
			</div>
		</div>
	</footer>
</div>
<script>

$(document).ready(function(){
	function footerFix(){
		var body = document.getElementsByTagName("body")[0];
		var bodyHeight = body.offsetHeight;
		var windowHeight = window.innerHeight;

		if(bodyHeight < windowHeight){
			$(".isfixed").addClass("fixed-bottom");
		}
		else{
			$(".isfixed").removeClass("fixed-bottom");
		}
	}
	footerFix();
	$(window).resize(function(){
		footerFix();	
	});
});
</script>
