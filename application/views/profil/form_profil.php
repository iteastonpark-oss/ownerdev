<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>

<?php
$controller = strtolower($this->uri->segment(1)).'/'. strtolower($this->uri->segment(2));
?>
<div class="card">
	<div class="card-header">
		<div class="btn-group float-right">
			<?php
			echo anchor('#modal_form',
					'<i class="fa fa-user"></i>',
					'data-id="' . $id_admin . '" data-toggle="modal" title="Edit Username"
                                    class="edit_profil btn btn-info"');
			echo anchor('#modal_form',
					'<i class="fa fa-key"></i>',
					'data-id="' . $id_admin . '" data-toggle="modal" title="Change Password"
                                    class="password_profil btn btn-warning"');
			/*
			echo anchor('#modal_form',
					'<i class="fa fa-signature"></i>',
					'data-id="' . $id_admin . '" data-toggle="modal" title="Signature"
                                    class="signature btn btn-success"');
			*/
			echo anchor(site_url('profil/signature?id=' . $id_admin),
					'<i class="fa fa-signature"></i>',
					'title="Signature" class="btn btn-success"');

			?>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-borderless table-sm table-striped">
			<tr>
				<th class="pull-right">Nama Lengkap</th>
				<td><span id="nama_admin"></span></td>
			</tr>
			<tr>
				<th class="pull-right">Username
				</td>
				<td><span id="username"></span></td>
			</tr>
		</table>

		<hr>
		<?php $this->load->view('karyawan/detail/profil'); ?>

	</div>

</div>

<script type="text/javascript">


	$(document).ready(function () {
		data_profil();
	});

	function data_profil() {
		var url = "<?php echo site_url($controller . '/ajax_get_profil/' . $id_admin);?>"
		console.log(url);
		$('.loader').show();
		$.ajax({
			url: url,
			type: "GET",
			dataType: "JSON",
			success: function (data) {
				$.each(data, function (index, value) {
					$('.loader').hide();
					var profil = value.profil;
					$("#nama_admin").html(": " + profil.nama_admin);
					$("#username").html(": " + profil.username);
				})

			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Tidak Ada Data');
			}
		});
	}

	var user_id;
	var save_method;
	$(document).on("click", '.edit_profil', function (e) {
		save_method = 'edit_profil';
		user_id = $(this).data('id');
		$('#modal_form').modal('show');

		$('#btnSave').text('Edit Username').show();
		$('#text-hapus').hide(); //
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/edit_profil/') ?>?id=" + user_id;
		$('.modal-title').text("Edit Username"); // Set title to Bootstrap modal title
		$.post(
				url,
				function (data) {
					$("#page-view").html(data).show();
				}
		);
	});
	$(document).on("click", '.password_profil', function (e) {
		save_method = 'password_profil';
		user_id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').text('Ganti Password').show();
		$('#text-hapus').hide(); //
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/password_profil/') ?>?id=" + user_id;
		$('.modal-title').text("Edit Password"); // Set title to Bootstrap modal title
		$.post(
				url,
				function (data) {
					$("#page-view").html(data).show();
				}
		);
	});

	$(document).on("click", '.signature', function (e) {
		save_method = 'signature';
		user_id = $(this).data('id');
		$('#modal_form').modal('show');
		$('#btnSave').text('Signature').show();
		$('#text-hapus').hide(); //
		$('#form')[0].reset(); // reset form on modals
		var url = "<?php echo site_url($controller . '/signature_profil/') ?>?id=" + user_id;
		$('.modal-title').text("Edit Password"); // Set title to Bootstrap modal title
		$.post(
				url,
				function (data) {
					$("#page-view").html(data).show();
				}
		);
	});


	/**
	 * Simpan / Update /hapus (ACTION CRUD)
	 */

	$(document).ready(function () {
		$("#btnSave").on('click', function (e) {
			e.stopPropagation(); // Jika gagal masih muncul
			save();
		});

		function save() {
			if (save_method == 'edit_profil') {
				url = "<?php echo site_url($controller . '/ajax_edit_profil'); ?>";
			}
			if (save_method == 'password_profil') {
				url = "<?php echo site_url($controller . '/ajax_password_profil'); ?>";
			}
			if (save_method == 'signature') {
				url = "<?php echo site_url($controller . '/ajax_add_signature'); ?>";
			}
			var data = $('#form').serialize();
			//alert(url + data);
			$("#form").validate({
				rules: {
					name: "required"
				},
				messages: {
					required: "This field is required.",
				},
				submitHandler: function (form) {
					$.ajax({
						url: url,
						type: "POST",
						processData: false,
						contentType: false,
						data: new FormData($('#form')[0]),

						dataType: "JSON",
						success: function (data) {
							//if success close modal and reload ajax table
							$('#modal_form').modal('hide');//Hide Modal Bootstrap
							// location.reload();
							data_profil();
							notifikasi();
						},
						error: function (jqXHR, textStatus, errorThrown) {
							//location.reload();
							data_profil();
						}
					});

				}
			});
			e.preventDefault();
		}
	});


</script>


<!-- Bootstrap modal -->
<div class="modal fade bd-example-modal-lg" data-backdrop="static" id="modal_form" role="dialog">
	<div class="modal-dialog  modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form" method="post" class="form-horizontal" onsubmit="return false">
				<div class="modal-body">
					<div class="container-fluid">
						<div id="page-hapus"></div>
						<div id="page-input"></div>
						<div id="page-view"></div>
					</div>
				</div>
				<div class="modal-footer">
					<?php echo $this->tombol->get_simpan_js("save()", "cancel()"); ?>
				</div>
			</form>
		</div>
	</div>
</div>
