<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 7:02 PM
 */
?>

<footer class="footer bg-transparent">
	<ul class="list-group text-right w-100">
		<li class="list-group-item list-group-item-primary">Hotline</li>
		<li class="list-group-item list-group-item-success"><a href="https://wa.me/6282312122021" target="_blank">082312122021
				<i class="fa fa-whatsapp"></i></a></li>
		<li class="list-group-item list-group-item-success">0227780188 <i class="fa fa-phone"></i></li>
		<li class="list-group-item list-group-item-success"><a href="https://eprjatinangor.com" target="_blank">https://eprjatinangor.com
				<i class="fa fa-globe"></i></a></li>
		<li class="list-group-item list-group-item-success"><a href="mailto:info@eprjatinangor.com" target="_blank">info@eprjatinangor.com
				<i class="fa fa-send"></i></a></li>
	</ul>
	<div class="align-items-center justify-content-xl-between mb-5">
		<div class="col-xl-6">
			<div class="copyright text-center text-xl-left text-muted">
				&copy; <?php echo date('Y'); ?> <a href="https://eprjatinangor.com" class="font-weight-bold ml-1"
												   target="_blank"><?= $this->bm_model->get()->footer; ?></a>
			</div>
		</div>
		<div class="col-xl-6">
			<!--
			<ul class="nav nav-footer justify-content-center justify-content-xl-end">
				<li class="nav-item">
					<a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
				</li>
				<li class="nav-item">
					<a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About
						Us</a>
				</li>
				<li class="nav-item">
					<a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
				</li>
				<li class="nav-item">
					<a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md"
					   class="nav-link" target="_blank">MIT License</a>
				</li>
			</ul>
			-->
		</div>
	</div>
</footer>


<script src="<?php echo base_url('assets/jquery.validate.min.js') ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/js-cookie/js.cookie.js'); ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/jquery.scrollbar/jquery.scrollbar.min.js'); ?>"></script>
<!--
<script
		src="<?php echo base_url('assets/argon/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js'); ?>"></script>
-->
<!-- Optional JS -->
<script src="<?php echo base_url('assets/argon/vendor/chart.js/dist/Chart.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/chart.js/dist/Chart.extension.js'); ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/@fortawesome/fontawesome-free/js/all.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/@fortawesome/fontawesome-free/js/v4-shims.min.js'); ?>"></script>

<!--
<script src="<?php echo base_url('assets/argon/vendor/moment/min/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/argon/vendor/fullcalendar/dist/fullcalendar.min.js'); ?>"></script>
-->
<!-- Argon JS -->
<script src="<?php echo base_url('assets/argon/js/argon.js?v=1.0.0'); ?>"></script>


<!-- Demo JS - remove this in your project -->
<!--
<script src="<?php echo base_url('assets/argon/js/demo.min.js'); ?>"></script>
-->
<script type="module">
	// Import the functions you need from the SDKs you need

	import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
	import {getAnalytics} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-analytics.js";
	import {getMessaging, onMessage, getToken} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";
	/*
	import {initializeApp} from "firebase/app";
	import {getAnalytics} from "firebase/analytics";
	import {getMessaging} from "firebase/messaging";
	import {onMessage} from "firebase/messaging";
	*/
	// TODO: Add SDKs for Firebase products that you want to use

	// https://firebase.google.com/docs/web/setup#available-libraries

	// Your web app's Firebase configuration
	// For Firebase JS SDK v7.20.0 and later, measurementId is optional
	const firebaseConfig = {
		apiKey: "AIzaSyBhtFn5_2yoZccpAJ0N1nQrFOqoaoa5CEU",
		authDomain: "bms-eprj.firebaseapp.com",
		projectId: "bms-eprj",
		storageBucket: "bms-eprj.appspot.com",
		messagingSenderId: "329281976225",
		appId: "1:329281976225:web:6fe0338245decdec233de0",
		measurementId: "G-SLFM0QGMFT"
	};

	// Initialize Firebase
	const app = initializeApp(firebaseConfig);
	const analytics = getAnalytics(app);
	const messaging = getMessaging(app);
	//console.log(messaging);
	messaging
		.requestPermission()
		.then(() => {
			message.innerHTML = "Notifications allowed";
			return messaging.getToken();
		})
		.then(token => {
			console.log("Token Is : " + token);
		})
		.catch(err => {
			console.log("No permission to send push", err);
		});

	messaging.setBackgroundMessageHandler(payload => {
		const notification = JSON.parse(payload.data.notification);
		const notificationTitle = notification.title;
		const notificationOptions = {
			body: notification.body
		};
		//Show the notification :)
		return self.registration.showNotification(
			notificationTitle,
			notificationOptions
		);
	});

	onMessage(messaging, (payload) => {
		console.log('Received background message ', payload);
		// Customize notification here

		const notificationTitle = 'Background Message Title';
		const notificationOptions = {
			body: 'Background Message body.',
			icon: 'assets/icons/icon.png'
		};

		self.registration.showNotification(notificationTitle,
			notificationOptions);
	});

	/*
	getToken(messaging, {vapidKey: 'BFu1tYU5c2q2qs1IL0xbMX7EIvgjWyRM2GVpdqcYgpnWj_NUwh87mOZa6NR4mrjs3uGVc88hrYahDApWZU1uybg'})
		.then((currentToken) => {
			if (currentToken) {
				// Send the token to your server and update the UI if necessary
				// ...
			} else {
				// Show permission request UI
				console.log('No registration token available. Request permission to generate one.');
				// ...
			}
		}).catch((err) => {
		console.log('An error occurred while retrieving token. ', err);
		// ...
	});
	*/
</script>
