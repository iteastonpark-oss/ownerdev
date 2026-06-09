<?php
$controller = $this->uri->segment(1);
?>

<div class="card">
	<?php $this->load->view('unit/bast/menu'); ?>
	<div class="card-body">
		<div class="card-title"><?= $judul; ?></div>
		<table class="table table-hover dt-responsive nowrap table-striped table-sm" width="100%"
			   cellspacing="0">
			<tr class="table-primary">
				<?php
				foreach ($list->list_fields() as $tr) {
					echo '<th>' . $tr . '</th>';
				}
				?>
			</tr>
			</thead>
			<?php
			$no = 0;
			$jumlah = $list->num_fields();
			foreach ($list->result_array() as $data) {
				$no++;
				echo '<tr>';
				for ($i = 0; $i < $jumlah; $i++) {
					$r = array_values($data);
					$r[0] = $no;
					$r[3] = $this->apl->tgl_format($r[3], 1);
					$r[$jumlah - 1] = '<div class="btn-group"> '
						. anchor('#modal_form'
							, '<i class="fa fa-search"></i>'
							, 'data-id="' . $r[$jumlah - 1] . '" data-toggle="modal"
                           class="lihat btn btn-primary btn-sm"  title="Lihat Izin Huni"')
						/*
						. '<a href="' . site_url('unit/bast/detail/?id=' . $r[$jumlah - 1]) . '"
                            class="btn btn-info btn-sm" title="lihat detail"><i class="fa fa-search"></i></a>'
						*/
						. '</div>';
					echo '<td>' . $r[$i] . '</td>';
				}
				echo '</tr>';
			}
			?>
		</table>


	</div>

</div>

<script>

	$(document).on("click", '.lihat', function (e) {

		id = $(this).data("id");
		save_method = 'lihat';
		$('#modal_form').modal('show');
		$('#btnSave').html("").hide();
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url('unit/huni/detail/') ?>" + id;
		$('.modal-title').text("Detail Izin Huni"); // Set title to Bootstrap modal title
		$.post(
			url,
			function (data) {
				$("#page-view").html(data).show();
			}
		);
	});
</script>
