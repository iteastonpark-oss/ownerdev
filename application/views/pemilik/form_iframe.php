<style>
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
	}
	iframe {
		width: 100%;
		height: 100%;
		border: none;
		display: block;
	}
</style>
<div class="mt--5" style="z-index: 9999">
	<iframe id="laporanFrame" src="<?php echo $iframe_url; ?>"></iframe>
</div>

<script>
	// Auto set tinggi iframe sesuai tinggi layar
	function resizeIframe() {
		var iframe = document.getElementById('laporanFrame');
		iframe.style.height = window.innerHeight + 'px';
	}
	window.addEventListener('resize', resizeIframe);
	resizeIframe();
</script>
