<table class="table table-sm small table-borderless table-striped">
	<tr>
		<td>Meter Awal</td>
		<td><?= ($m->meter - $m->pakai); ?> M2</td>
	</tr>
	<tr>
		<td>Meter Ahir</td>
		<td><?= $m->meter; ?> M2</td>
	</tr>
	<tr>
		<td>Pemakaian</td>
		<td><?= $m->pakai; ?> M2</td>
	</tr>
</table>

<?php
$target_file = "meter";
$gambar = $m->file;
if ($m->app = 1) {
	$url = 'https://localhost/bms_apps/upload';
} else {
	$url = base_url();
}
$cek_gambar = $url . '/' . $target_file . '/' . $gambar;
//echo base_url() . '/upload/no_pict.png';
?>
<img src="<?= $cek_gambar; ?>" id="img-meter" class="img-thumbnail">
