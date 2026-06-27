<style>

	page {
		background: white;
		display: block;
		margin: 0 auto;
		margin-bottom: 0.5cm;
		box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
	}

	page[size="A4"] {
		width: 21cm;
		height: 29.7cm;
	}

	page[size="A4"][layout="landscape"] {
		width: 29.7cm;
		height: 21cm;
	}

	page[size="A3"] {
		width: 29.7cm;
		height: 42cm;
	}

	page[size="A3"][layout="landscape"] {
		width: 42cm;
		height: 29.7cm;
	}

	page[size="A5"] {
		width: 14.8cm;
		height: 21cm;
	}

	page[size="A5"][layout="landscape"] {
		width: 21cm;
		height: 14.8cm;
	}

	@media print {
		page {
			margin: 0;
			box-shadow: 0;
		}
	}


	#image {

		position: fixed;
		width: 500px;
		height: 200px;
		margin: 5% auto; /* Will not center vertically and won't work in IE6/7. */
		top: 100px;
		left: 0;
		right: 0;
		opacity: 0.2; /* Firefox, Chrome, Safari, Opera, IE >= 9 (preview) */
		filter: alpha(opacity=20); /* for <= IE 8 */
	}
</style>


<img src="<?php echo $this->upload_model->link($this->bm_model->get()->logo, "logo"); ?>" width="200" height="50"
	 id="image">
<table style="width: 100%" class="table-borderless">
	<tr>
		<td><img src="<?php echo base_url('upload/logo/' . $this->bm_model->get()->logo); ?>"
				 class="text-center" alt="" width="25%">
		</td>
		<td class="text-center">
			<?php
			//echo $judul;
			//echo (isset($judul)) ? strtoupper($judul) : '';
			?>
		</td>
		<td class="float-right">
			<small>
				<?php echo $this->bm_model->get()->nama; ?><br>
				<?php echo $this->bm_model->get()->alamat; ?><br>
				<?php echo $this->bm_model->get()->kota; ?>, <?php echo $this->bm_model->get()->provinsi; ?><br>
				No. Telp. <?php echo $this->bm_model->get()->telp; ?><br>
			</small>
		</td>
	</tr>
</table>
<div class="text-center justify-content-center">
	<h4 class=" "><?php
		//echo $judul;
		echo (isset($judul)) ? strtoupper($judul) : '';
		?>
	</h4>
</div>
