<title><?php time() . " Kwt-Bayar"; ?></title>
<?php
//echo $ka;
//die();

if ($tipe == 'print') {
	?>

	<script type="text/javascript">
		var is_chrome = function () {
			return Boolean(window.chrome);
		}
		window.onload = function () {
			if (is_chrome) {
				/*
				 * These 2 lines are here because as usual, for other browsers,
				 * the window is a tiny 100x100 box that the user will barely see.
				 * On Chrome, it needs to be big enough for the dialogue to be read
				 * (NB, it also includes a page preview).
				 */
				window.moveTo(0, 0);
				window.resizeTo(640, 480);

				// This line causes the print dialogue to appear, as usual:
				//window.print();

				/*
				 * This setTimeout isn't fired until after .print() has finished
				 * or the dialogue is closed/cancelled.
				 * It doesn't need to be a big pause, 500ms seems OK.
				 */
				setTimeout(function () {
					//window.close();
				}, 500);
			} else {
				// For other browsers we can do things more briefly:
				window.print();
				window.close();
			}
		}
		/*
		window.onload = function () {
			window.print();
			window.close();
		}
		*/
	</script>

	<?php
	$this->load->view('layout/header');
	?>


	<style>


	</style>
<?php } ?>
<div class="container text-monospace">
	<div class="card">

	<?php $this->load->view('layout/kepala_cetak'); ?>
		<hr>
		<table class="table table-datatables nowrap table-sm">
			<thead class="table-secondary">
			<tr class="active bg-active">
				<td>No Form</td>
				<td>Date</td>
				<td>Invoice</td>
				<td>Fine</td>
				<td>Payment/Credit Note</td>
				<td>Balance</td>
			</tr>
			</thead>
			<tbody>
			<?php
			$sisa = 0;
			foreach ($h->result_array() as $data) {
				$r = array_values($data);
				$sisa = $sisa + $r[4] + $r[5] - $r[6];
				echo '<tr>';
				//echo '<td>'.$r[0].'</td>';
				echo '<td>' . $r[1] . '</td>';
				echo '<td>' . $r[2] . '</td>';
				echo '<th>Rp. <span class="float-right">' . $this->apl->number_format($r[4], 1) . '</span></th>';
				echo '<th>Rp. <span class="float-right">' . $this->apl->number_format($r[5], 1) . '</span></th>';
				echo '<th>Rp. <span class="float-right">' . $this->apl->number_format($r[6], 1) . '</span></th>';
				echo '<th>Rp. <span class="float-right">' . $this->apl->number_format($sisa, 1) . '</span></th>';
				echo '</tr>';
			}
			?>
			</tbody>
			<tfoot>
			<tr class="active bg-secondary">
				<td></td>
				<th>Total</th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($total_tagihan, 1); ?></span></th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($total_denda, 1); ?></span></th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($total_bayar, 1); ?></span></th>
				<th>Rp. <span class="float-right"><?= $this->apl->number_format($total_piutang, 1); ?></span></th>

			</tr>
			</tfoot>
		</table>

	</div>
</div>
