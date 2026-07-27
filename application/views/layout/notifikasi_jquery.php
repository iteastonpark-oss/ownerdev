<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/17/2016
 * Time: 12:40 AM
 */
?>
<style>
	/* Hanya badge angka di lonceng notifikasi navbar (#notifikasi). Selektor `.badge`
	   polos sebelumnya membuat SEMUA badge di aplikasi ikut position:absolute + dipaksa
	   hijau, sehingga badge status (mis. di tabel invoice) melayang keluar dari selnya. */
	#notifikasi .badge {
		position: absolute;
		background: #289679;
		top: -3px;
	}

	/**alert POP UP**/

	.alert-popup {
		position: fixed;
		z-index: 10000;
		bottom: 0px;
		left: 20px;
		width: 30%;
		border-radius: 2px
	}

	.progress-bar {
		transition-duration: 3s;
	}

	@media (max-width: 800px) {
		.alert-popup {
			width: 90%;
		}
	}

	@media (max-width: 420px) {
		.alert-popup {
			width: 90%;
		}
	}

	@media (max-width: 360px) {
		.alert-popup {
			width: 90%;
		}
	}

	@media only screen and (min-width: 320px) and (max-device-width: 480px) {
		.alert-popup {
			width: 90%;
		}
	}

</style>

<div class="notifikasi"></div>

<script>

	$(document).ready(function () {
		notifikasi();
	});

	function notifikasi() {
		notifikasi_alert();
		
	}

	function notifikasi_alert() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('notifikasi/pesan');?>",
			dataType: 'json',
			success: function (response) {
				$(".notifikasi").html("" + response.pesan + "");

				$('.progress .progress-bar').css("width",
						function () {
							return $(this).attr("aria-valuenow") + "%";
						}
				)
				window.setTimeout(function () {
					$(".alert-popup").fadeTo(3000, 0).slideUp(3000, function () {
						$(this).remove();
					});
				}, 3000);
			}
		});
	}

</script>
