<?php
$controller = $this->uri->segment(1);
?>

<div class="card">
	<?php $this->load->view('pemilik/menu'); ?>
	<div class="card-body">

		<div class="card-title"><?= $judul; ?></div>

		<table class="table table-hover dt-responsive nowrap table-striped table-sm" width="100%"
			   cellspacing="0">
			<tr class="table-primary">
				<?php
				foreach ($unit->list_fields() as $tr) {
					echo '<th>' . $tr . '</th>';
				}
				?>
			</tr>
			</thead>
			<?php
			$no = 0;
			$jumlah = $unit->num_fields();
			foreach ($unit->result_array() as $data) {
				$no++;
				echo '<tr>';
				for ($i = 0; $i < $jumlah; $i++) {
					$r = array_values($data);
					$r[0] = $no;

					$r[3] = $this->apl->tgl_format($r[3], 1);

					$r[$jumlah - 1] = '<div class="btn-group"> '
						. '<a href="' . site_url('unit/bast/detail/?id=' . $r[$jumlah - 1]) . '" 
                            class="btn btn-info btn-sm" title="lihat detail"><i class="fa fa-search"></i></a>'
						. '</div>';
					echo '<td>' . $r[$i] . '</td>';
				}
				echo '</tr>';
			}
			?>
		</table>


	</div>

</div>
