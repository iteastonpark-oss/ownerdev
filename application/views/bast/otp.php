<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/15/2016
 * Time: 9:06 AM
 */
?>
<style>
	body {
		background: #eee;
	}

	.bgWhite {
		background: white;
		box-shadow: 0px 3px 6px 0px #cacaca;
	}

	.title {
		font-weight: 600;
		margin-top: 20px;
		font-size: 24px
	}

	.customBtn {
		border-radius: 0px;
		padding: 10px;
	}

	form input {
		display: inline-block;
		width: 50px;
		height: 50px;
		text-align: center;
	}
</style>
<div class="card bg-gradient-gray-dark">
	<div class="card-body">
		<div class="main-content mt--9">

			<div class="container">
				<div class="header-body text-center">
					<div class="row justify-content-center">
						<div class="col-xl-5 col-lg-6 col-md-8 px-5">
							<h1 class="text-white">Verify OTP Whatsapp!</h1>
							<p class="text-lead text-white"></p>
						</div>
					</div>
				</div>
			</div>
			<!-- Page content -->

			<!-- Page content -->
			<div class="container mt--8">
				<div class="row justify-content-center">
					<div class="col-lg-5 col-md-7">
						<form action="<?php echo site_url('login/login_otp'); ?>" method="post">
							<div class="card bg-secondary border-0 mb-0 text-center">
								<div class="card-body px-lg-5 py-lg-5">
									<input class="otp " type="text" name="otp[]" oninput='digitValidate(this)'
										   onkeyup='tabChange(1)' maxlength=1>
									<input class="otp" type="text" name="otp[]" oninput='digitValidate(this)'
										   onkeyup='tabChange(2)' maxlength=1>
									<input class="otp" type="text" name="otp[]" oninput='digitValidate(this)'
										   onkeyup='tabChange(3)' maxlength=1>
									<input class="otp" type="text" name="otp[]" oninput='digitValidate(this)'
										   onkeyup='tabChange(4)' maxlength=1>
								</div>
								<div class="card-footer">
									<button class='btn btn-primary btn-block mt-4 mb-4 customBtn'>
										Verify
									</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<script>
	let digitValidate = function (ele) {
		console.log(ele.value);
		ele.value = ele.value.replace(/[^0-9]/g, '');
	}

	let tabChange = function (val) {
		let ele = document.querySelectorAll('input');
		if (ele[val - 1].value != '') {
			ele[val].focus()
		} else if (ele[val - 1].value == '') {
			ele[val - 2].focus()
		}
	}
</script>
