<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 3/26/2017
 * Time: 11:38 AM
 */
?>

<div class="loader"></div>
<style>

	.loader {
		border: 16px solid #f3f3f3;
		border-radius: 50%;
		border-top: 16px solid #3498db;
		width: 120px;
		height: 120px;
		-webkit-animation: spin 2s linear infinite; /* Safari */
		animation: spin 2s linear infinite;

		position: fixed;
		top: 48%;
		right: 40%;
		z-index: 9999;
	}

	/* Safari */
	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
	/*
	.loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        opacity: 0.8;
        position: fixed;
        top: 48%;
        right: 48%;
        z-index: 9999;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .loader {
        border-top: 10px solid white;
        border-right: 10px solid white;
        border-bottom: 10px solid gray;
    }

	 */
</style>
<script>
    $(document).ready(function () {
        $('.loader').hide();
    });
</script>
