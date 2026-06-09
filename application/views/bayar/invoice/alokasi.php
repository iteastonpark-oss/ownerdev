<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 9/22/2016
 * Time: 9:30 AM
 */
?>

<script src="<?php echo base_url('assets/getNumber.js') ?>"></script>
<h3>Rincian Alokasi</h3>
<input type="hidden" name="id_bayar" value="<?php echo ($submit == 'edit') ? $bayar->id_bayar : ''; ?>">
<input type="hidden" name="id_bast" value="<?= $bast->id_bast; ?>">

<h4>Daftar Piutang</h4>

<table class="table table-borderless table-sm">
	<thead class="table-active">
	<tr>
		<td>Nama Tagihan</td>
		<td>Jumlah</td>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	foreach ($piutang as $p) {
		?>
		<tr>
			<td>
				<?php
				echo ($p->id_tag != null) ? $this->apl->get_nilai_pilih("db_tag"
					, "nama", array('id_tag' => $p->id_tag)) : 'UnAlokasi'; ?>
			</td>
			<td class="pull-right">

				<?= $this->apl->number_format($p->jumlah, 1); ?>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
<hr>
<div class="form-group">
	<label for="">Jumlah UnAlokasi</label>
	<input type="text" name="alokasi" class="form-control form-group-sm getNumber" id="alokasi"
		   value="<?= $alokasi->jumlah; ?>" readonly>
	<input type="hidden" name="id_detail" value="<?= $alokasi->id_detail;?>">
</div>
<div class="row">
	<div class="col-md-4">
		<?php
		echo $this->dropdown_model->getDropdownTagihanInvoiceDanDeposit('', '',
			'class="form-control"
							id="input_tagihan"')
		?>

	</div>
	<div class="col-md-2">
		<input type="text" id="input_jumlah"
			   class="form-control getNumber" value="0"
			   required>
	</div>
	<div class="col-md-4">
		<div class="input-group">
			<?php
			echo $this->dropdown_model->getDropdownBulan("bulan_dari", date('m'),
				'class="form-control" id="input_bulan"')
			?>

			<?php
			echo $this->dropdown_model->getDropdownTahun("tahun_dari", date('Y'),
				'class="form-control" id="input_tahun"')
			?>
		</div>
	</div>
	<div class="col-md-2">
		<button type="button" name="add" id="add"
				class="btn btn-success">
			<i class="fa fa-plus"></i> Add
		</button>
	</div>
</div>
<hr>
<table class="table table-borderless table-sm">
	<thead class="table-active">
	<tr>
		<td>Invoice</td>
		<td>Nama Tagihan</td>
		<td>Jumlah</td>
		<td>Bulan</td>
		<td>Tahun</td>
		<td>Aksi</td>
	</tr>
	</thead>
	<tbody id="dynamic_field">
	<?php
	$i = 0;
	foreach ($b as $b) {
		?>
		<tr id="row2<?php echo $b->id_detail; ?>" class="dynamic-added">
			<td>
				<?= $this->apl->get_nilai_pilih("billing"
					, "invoice", array('id_billing' => $b->id_billing)); ?>
				<input type="hidden" name="id_billing[]" class="form-control form-control-sm"
					   value="<?php echo $b->id_billing; ?>"
					   required>
			</td>
			<td>
				<?php
				echo ($b->id_tag != null) ? $this->apl->get_nilai_pilih("db_tag"
					, "nama", array('id_tag' => $b->id_tag)) : 'UnAlokasi'; ?>
				<input type="hidden" name="id_tag[]" class="form-control form-control-sm"
					   value="<?php echo $b->id_tag; ?>"
					   required>

			</td>
			<td class="pull-right">

				<?= $this->apl->number_format($b->jumlah, 1); ?>
				<input type="hidden" name="jumlah[]" class="form-control form-control-sm"
					   value="<?php echo $b->jumlah; ?>"
					   required>
			</td>
			<td>
				<?= $b->bulan; ?>
				<input type="hidden" name="bulan[]" class="form-control form-control-sm"
					   value="<?php echo $b->bulan; ?>"
					   required>
			</td>
			<td>
				<?= $b->tahun; ?>
				<input type="hidden" name="tahun[]" class="form-control form-control-sm"
					   value="<?php echo $b->tahun; ?>"
					   required>
			</td>

			<td>
				<button type="button" name="remove" data-id="<?php echo $b->id_detail; ?>"
						class="btn btn-danger btn-sm btn_remove">X
				</button>
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>


	<tfoot class="table-primary">
	<tr>
		<th class="pull-right">Total</th>
		<th></th>
		<th></th>
		<th>
			<div class="pull-right" id="total"></div>
		</th>
		<th></th>
		<th></th>
	</tr>
	</tfoot>


</table>


<script>

	var jumlah = 0;
	var tagihan = 0;
	var alokasi = 0;
	$(document).ready(function () {
		total();
	});

	function total() {
		jumlah = 0;
		alokasi = "<?php echo $alokasi->jumlah;?>";
		$('input[name^="jumlah"]').each(function () {
			jumlah += parseFloat($(this).val());
		});
		alokasi = alokasi - jumlah;
		$("#total").html(jumlah.toLocaleString("en-US"));
		document.getElementById("alokasi").value = alokasi;
	}

	var i = 1;
	$("#add").click(function () {
		var id_tag = $('#input_tagihan').val();
		var jumlah = $('#input_jumlah').val();
		var bulan = $('#input_bulan').val();
		var tahun = $('#input_tahun').val();

		if (id_tag == '') {
			alert("Harap masukan Jenis Tagihan");
			return (false);
		}
		if (jumlah == 0) {
			alert("Harap masukan harga");
			return (false);
		}
		if (bulan == '') {
			alert("Harap masukan Bulan");
			return (false);
		}
		if (tahun == '') {
			alert("Harap masukan Tahun");
			return (false);
		}

		i++;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('bayar/invoice/alokasi_bayar_detail') ?>",
			cache: false,
			data: {id_tag: id_tag, jumlah: jumlah, bulan: bulan, tahun: tahun},

			success: function (respond) {

				$('#dynamic_field').append('' +
					'<tr id="row' + i + '" class="dynamic-added">' + respond +
					'<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-sm btn_remove">X</button></td>' +
					'</tr>');

				total();
			}
		});


	});


	$(document).on('click', '.btn_remove', function () {
		var button_id = $(this).attr("id");
		$('#row' + button_id + '').remove();
		var button_id2 = $(this).data("id");
		$('#row2' + button_id2 + '').remove();

		total();
	});


	function validate(form) {
		if (jumlah == 0) {
			alert("Harap masukan Atribut Access Card");
			return (false);
		}
		return (true);
	}

</script>
