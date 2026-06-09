<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:58 PM
 */
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");


//header_remove('Cache-Control');

$pesan = new Pesan();
$this->pesan = new Pesan();
$tombol = new Tombol();
$this->tombol = new Tombol();
$apl = new Apl();
$this->apl = new Apl();

$dropdown_model = new Dropdown_Model();
$this->dropdown_model = new Dropdown_Model();


require_once 'layout/header.php';
require_once 'layout/notifikasi_jquery.php';
//require_once 'layout/loader.php';


$sidebar = "layout/sidebar";
$navbar = "layout/navbar";
?>


<body class="bg-secondary">

<script>
	var BASE_URL = '<?= base_url() ?>';
	document.addEventListener('DOMContentLoaded', init, false);
	let swRegistration = null;

	function init() {
		/*
		if ('serviceWorker' in navigator && navigator.onLine) {
			navigator.serviceWorker.register(BASE_URL + 'service-worker.js')
				.then((reg) => {
					console.log('Registrasi service worker Berhasil', reg);
				}, (err) => {
					console.error('Registrasi service worker Gagal', err);
				});
		}
		 */

		if ('serviceWorker' in navigator && 'PushManager' in window) {
			navigator.serviceWorker
				.register(BASE_URL + 'service-worker.js')
				//.register(BASE_URL + 'firebase-messaging-sw.js')
				.then((reg) => {
					console.log('Registrasi service worker Berhasil', reg);
					swRegistration = reg;

				}, (err) => {
					console.error('Registrasi service worker Gagal', err);
				}).catch(error => {
				console.error('Service Worker Error', error);
			});
		}


		/*
		if (window.Notification && Notification.permission === "granted") {
			displayNotification('Notification Enabled');

		} else if ('Notification' in window && Notification.permission != 'granted') {
			console.log('Ask user permission')
			Notification.requestPermission(status => {
				console.log('Status:' + status)
				displayNotification('Notification Enabled');
			});
		}
		*/
	}

	/*
	const displayNotification = notificationTitle => {
		console.log('display notification')
		if (Notification.permission == 'granted') {
			navigator.serviceWorker.getRegistration().then(reg => {
				console.log(reg)
				const options = {
					body: 'Thanks for allowing push notification !',
					icon: 'assets/icons/icon.png',
					vibrate: [100, 50, 100],
					data: {
						dateOfArrival: Date.now(),
						primaryKey: 0
					}
				};

				swRegistration.showNotification(notificationTitle, options);
			});
		}
	};
	*/

	/**
	 *
	 * Cek Register Firebase
	 */

	/*
	const updateSubscriptionOnYourServer = subscription => {
		console.log('Write your ajax code here to save the user subscription in your DB', subscription);
		// write your own ajax request method using fetch, jquery, axios to save the subscription in your server for later use.
	};

	const subscribeUser = async () => {
		const swRegistration = await navigator.serviceWorker.getRegistration();
		const applicationServerPublicKey = 'BFu1tYU5c2q2qs1IL0xbMX7EIvgjWyRM2GVpdqcYgpnWj_NUwh87mOZa6NR4mrjs3uGVc88hrYahDApWZU1uybg'; // paste your webpush certificate public key
		const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
		swRegistration.pushManager.subscribe({
			userVisibleOnly: true,
			applicationServerKey
		})
			.then((subscription) => {
				console.log('User is subscribed newly:', subscription);
				updateSubscriptionOnYourServer(subscription);
			})
			.catch((err) => {
				if (Notification.permission === 'denied') {
					console.warn('Permission for notifications was denied')
				} else {
					console.error('Failed to subscribe the user: ', err)
				}
			});
	};
	const urlB64ToUint8Array = (base64String) => {
		const padding = '='.repeat((4 - base64String.length % 4) % 4)
		const base64 = (base64String + padding)
			.replace(/\-/g, '+')
			.replace(/_/g, '/')

		const rawData = window.atob(base64);
		const outputArray = new Uint8Array(rawData.length);

		for (let i = 0; i < rawData.length; ++i) {
			outputArray[i] = rawData.charCodeAt(i);
		}
		return outputArray;
	};

	const checkSubscription = async () => {
		const swRegistration = await navigator.serviceWorker.getRegistration();
		swRegistration.pushManager.getSubscription()
			.then(subscription => {
				if (!!subscription) {
					console.log('User IS Already subscribed.');
					updateSubscriptionOnYourServer(subscription);
				} else {
					console.log('User is NOT subscribed. Subscribe user newly');
					subscribeUser();
				}
			});
	};

	checkSubscription();
	*/
</script>
<?php
if ($this->session->login) {
	if ($this->session->tipe == 'owner') {
		$this->load->view($sidebar);
		//$this->load->view('layout/bottombar');
	}
}
?>
<div class="main-content " id="panel">
	<?php
	$this->load->view($navbar);
	?>
	<!-- Page content -->
	<div class="container-fluid bg-secondary mt--4">

		<?php
		if ($this->session->login) {

			if ($this->session->login == 1 && $this->session->tipe == 'owner') {
				require_once $page . '.php';
			} else {
				$this->load->view('layout/error_404');
			}
		} else {
			require_once 'login/login.php';
		}
		?>
	
		<?php
		$this->load->view('layout/footer');
		?>


	</div>


</div>
</body>


</html>


<?php
//print_r($this->session->userdata());


?>
<script>
	$(document).ready(function () {

		if (window.history && window.history.pushState) {
			$('#modal_form').on('show.bs.modal', function (e) {
				window.history.pushState('forward', null, '');
			});

			$(window).on('popstate', function () {
				$('#modal_form').modal('hide');
			});
		}


		$('#page-hapus').hide(); //
		$('#page-input').hide(); //
		$('#page-view').hide(); //
		$('#page-menu').hide(); //


	});
	$(document).on("hidden.bs.modal", function (e) {

		$('#page-hapus').hide(); //
		$('#page-input').hide(); //
		$('#page-view').hide(); //
		$('#page-menu').hide(); //
		$('#btnSave').hide();
		$(".modal-footer").show();
		$('#btnSave').prop('disabled', false);


	});


</script>

<!-- Bootstrap modal -->
<div class="modal fade bd-example-modal-lg" data-backdrop="static" id="modal_form" role="dialog">
	<div class="modal-dialog  modal-lg modal-full">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="post" class="form-horizontal">
				<div class="modal-body">
					<div class="container-fluid">
						<div id="page-hapus"></div>
						<div id="page-input"></div>
						<div id="page-view"></div>
						<div id="page-menu"></div>
					</div>
				</div>
				<div class="modal-footer">
					<?php
					echo $this->tombol->get_simpan_js("save()", "cancel()");
					?>
				</div>
			</form>
		</div>
	</div>
</div>


