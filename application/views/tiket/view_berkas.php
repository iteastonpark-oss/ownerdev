<table class="table table-borderless">
	<thead>
	<tr>
		<td>Name</td>
		<td>Number</td>
		<td>File</td>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	foreach ($berkas as $b) {
		?>
		<tr>
			<td><?= $b->nama ?></td>
			<td><?= $b->nomor ?></td>
			<td>
				<?php
				$cek_foto = $this->upload_model->cek_link($b->file, $b->folder);
				if ($cek_foto == '1') {
					echo $this->upload_model->tampil_gambar_modal($b->file, "", $b->folder);
				}

				?>
			</td>
		</tr>

		<?php
	} ?>
	</tbody>
</table>
