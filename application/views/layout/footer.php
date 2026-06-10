<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 1/22/2019
 * Time: 7:02 PM
 */
?>
<!-- Footer -->
<footer
	class="bg-surface-container-lowest text-on-surface-variant w-full py-lg px-margin-desktop border-t border-outline-variant mt-auto">
	<div class="max-w-max-width mx-auto flex flex-col md:flex-row justify-between items-center gap-md">
		<div class="flex flex-col items-center md:items-start gap-xs">
			<div class="font-label-md text-label-md font-bold text-primary">Easton Park Residence Jatinangor</div>
			<div class="font-label-sm text-label-sm text-on-surface-variant">© 2024 Easton Park Residence
				Jatinangor. All rights reserved.</div>
		</div>
		<div class="flex flex-wrap justify-center gap-lg">
			<div class="flex flex-col items-center md:items-start">
				<span
					class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant/60 mb-xs">Inquiries</span>
				<div class="flex flex-col gap-1">
					<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary underline-offset-4 hover:underline transition-colors flex items-center gap-xs"
						href="tel:082312122021">
						<span class="material-symbols-outlined text-[16px]">call</span>
						Hotline: 082312122021
					</a>
					<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary underline-offset-4 hover:underline transition-colors flex items-center gap-xs"
						href="mailto:info@eprjatinangor.com">
						<span class="material-symbols-outlined text-[16px]">mail</span>
						info@eprjatinangor.com
					</a>
				</div>
			</div>
			<div class="flex flex-col items-center md:items-start">
				<span
					class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant/60 mb-xs">Legal</span>
				<div class="flex gap-md">
					<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary underline-offset-4 hover:underline transition-colors"
						href="#">Privacy Policy</a>
					<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary underline-offset-4 hover:underline transition-colors"
						href="#">Terms of Service</a>
				</div>
			</div>
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

        // Simple entry animation
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.animate-fade-in-up');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            container.style.transition = 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)';

            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });

        // Form handling simulation
        const loginForm = document.querySelector('form');
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = loginForm.querySelector('button');
            const originalContent = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span> Authenticating...`;

            setTimeout(() => {
                btn.classList.replace('bg-primary-container', 'bg-green-600');
                btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">check_circle</span> Success`;
                setTimeout(() => {
                    alert('Login successful! Redirecting to dashboard...');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                    btn.classList.remove('bg-green-600');
                }, 1000);
            }, 1500);
        });
    </script>
