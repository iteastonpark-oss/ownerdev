<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/11/2016
 * Time: 10:34 AM
 */
?>
<script src="<?php echo base_url('assets/custom.js') ?>"></script>
<div class="container-fluid">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<?php
	if($unit->id_group=='1')
	{
		$this->load->view('billing/invoice/print_isi');
	} else {
		$this->load->view('billing/invoice/print_isi_ca');
	}

	if ($bast->kirim == 2) {
		echo '<div class="btn-group">';
		$email = explode(',', $bast->email_surat);
		/*
		$pesan = 'Kepada Yth, Pemilik Unit Easton Park,
Berikut kami lampirkan invoice Easton Park Residence unit B0949 & B1146 a.n Prof. H. Dede Mariana, MSi periode Juli - September 2021. Demikian kami sampaikan, terima kasih atas perhatian dan kerjasamanya.';
		*/
		$unit = $unit->kode;
		$nama = str_replace(array(" ", ","), array("%20", "%2C"), $p->nama);
		$periode = str_replace(array(" ", ","), array("%20", "%2C"), $periode);
		$pesan = 'Kepada%20Yth%2C%20Pemilik%20Unit%20Easton%20Park%2C%0D%0A%0D%0ABerikut%20kami%20lampirkan%20invoice%20Easton%20Park%20Residence%20unit%20' . $unit
				. '%20a.n%20' . $nama
				. '%20periode%20' . $periode
				. '.%20Demikian%20kami%20sampaikan%2C%20terima%20kasih%20atas%20perhatian%20dan%20kerjasamanya.%0D%0A';

		//print_r($email);
		//echo implode(',', $email);


		
		echo '<a href="mailto:' . implode(';', $email) . ';'
				. '?Subject=Billing%20Statement'
				. '&body=' . $pesan . '" class="btn btn-primary"
				   target="_blank">' . implode(',', $email) . '</a>';
		/*
		echo '<a href="mailto:iy2.yustira@gmail.com;yusdeveloper@gmail.com;'
				. '?Subject=Billing%20Statement'
				. '&body=' . $pesan . '" class="btn btn-primary"
				   target="_blank">' . implode(',', $email) . '</a>';
		*/
		//for ($i = 0; $i < count($email); $i++) {
		/*
		echo '<a href="mailto:' . $email[$i] . '?Subject=Billing%20Statement&body=Message" class="btn btn-primary"
			   target="_blank">' . $email[$i] . '</a>';
		*/
		/*
		echo '<a href="mailto:iy2.yustira@gmail.com'
				. '?Subject=Billing%20Statement'
				. '&body=' . $pesan . '" class="btn btn-primary"
			   target="_blank">' . $email[$i] . '</a>';
	}
		*/

		/*
		if (count($email) > 1) {
			echo '<a href="mailto:' . str_replace(",", ";", $bast->email_surat)
					. '?Subject=Billing%20Statement&body=Message"
				   class="btn btn-success"
				   target="_blank"><i class="fa fa-list"></i> Email</a>';
		}
		*/
		echo '</div>';
	}
	?>
</div>

