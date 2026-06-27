<?php
$result = $this->db->from('admin_menu')->where(array('notifikasi' => 1))
		->where("id IN (" . implode(',', $this->login_model->user_menu()) . ")")
		->order_by('urut ASC')->get();
if ($result->num_rows() > 0) {
	?>

	<li class="nav-item dropdown">
		<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
		   aria-expanded="false">
			<i class="ni ni-check-bold"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-lg
		dropdown-menu-dark bg-gradient-info dropdown-menu-right">
			<div class="row shortcuts px-4">
				<?php

				$warna = array(
						'0' => 'red',
						'1' => 'primary',
						'2' => 'info',
						'3' => 'warning',
						'4' => 'success',
						'5' => 'light',
						'6' => 'dark',
						'7' => 'pink',
						'8' => 'default',
						'9' => 'indigo',
				);


				$total_not = 0;
				foreach ($result->result() as $menu2) {

					if ($menu2->site_url == 'divider') {
						?>
						<h6 class="col-12 dropdown-header mb-1"><?= $menu2->menu; ?></h6>
						<?php
					} else { ?>
						<a href="<?php echo site_url($menu2->site_url); ?>" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-<?= $warna[rand(0, 9)]; ?>">
                      <i class="fa fa-<?= $menu2->icon; ?>"></i>
                    </span>
							<?php
							if ($menu2->notifikasi_query != '') {
								$query = str_replace(array(
										'{{purchase_dep}}'
								), array(
										(!$this->apl->super_user()) ? " AND p_req.id_dep='" . $this->session->id_dep . "' " : '',
								), $menu2->notifikasi_query);

								//echo $query . "<br>";
								$total = $this->db->query($query)->row()->total;
								$total_not += $total;
								echo '<span class="badge badge-circle bg-primary my-0 text-right">' . $total . '</span>';

							}
							?>
							<small><?= $menu2->menu; ?></small>
						</a>
					<?php }
				} ?>

			</div>
		</div>
		<span class="badge badge-primary bg-neutral text-right mt--2"><?= $total_not; ?></span>
	</li>
<?php } ?>


<?php
$result = $this->db->from('admin_menu')->where(array('notifikasi' => 2))
		->where("id IN (" . implode(',', $this->login_model->user_menu()) . ")")
		->order_by('urut ASC')->get();
if ($result->num_rows() > 0) {
	?>

	<li class="nav-item dropdown">
		<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
		   aria-expanded="false">
			<i class="ni ni-settings-gear-65"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-gradient-info dropdown-menu-right">
			<div class="row shortcuts px-4">
				<?php
				$warna = array(
						'0' => 'red',
						'1' => 'primary',
						'2' => 'info',
						'3' => 'warning',
						'4' => 'success',
						'5' => 'light',
						'6' => 'dark',
						'7' => 'pink',
						'8' => 'default',
						'9' => 'indigo',
				);
				$total_not = 0;
				foreach ($result->result() as $menu2) {
					if ($menu2->site_url == 'divider') { ?>
						<h6 class="col-12 dropdown-header mb-1"><?= $menu2->menu; ?></h6>
						<?php
					} else { ?>
						<a href="<?php echo site_url($menu2->site_url); ?>" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-<?= $warna[rand(0, 9)]; ?>">
                      <i class="fa fa-<?= $menu2->icon; ?>"></i>
                    </span>
							<?php
							if ($menu2->notifikasi_query != '') {

								$query = str_replace(array(
										'{{request_dep}}'
								), array(
										(!$this->apl->super_user() and !$this->apl->tr() and !$this->apl->bm()) ? " AND tiket.id_dep='" . $this->session->id_dep . "' " : '',
								), $menu2->notifikasi_query);
								$total = $this->db->query($query)->row()->total;
								$total_not += $total;
								echo '<span class="badge badge-circle bg-primary my-0 text-right">' . $total . '</span>';
							}
							?>
							<small><?= $menu2->menu; ?></small>
						</a>
					<?php }
				} ?>

			</div>
		</div>

		<span class="badge badge-primary bg-neutral text-right mt--2"><?= $total_not; ?></span>

		<!--
		<span class="badge badge-info badge-neutral mt-3"></span>
		-->
	</li>
<?php } ?>

<?php
$result = $this->db->from('admin_menu')->where(array('notifikasi' => 3))
		->where("id IN (" . implode(',', $this->login_model->user_menu()) . ")")
		->order_by('urut ASC')->get();
if ($result->num_rows() > 0) {
	?>

	<li class="nav-item dropdown">
		<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
		   aria-expanded="false">
			<i class="fa fa-bank"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-gradient-info dropdown-menu-right">
			<div class="row shortcuts px-4">
				<?php
				$warna = array(
						'0' => 'red',
						'1' => 'primary',
						'2' => 'info',
						'3' => 'warning',
						'4' => 'success',
						'5' => 'light',
						'6' => 'dark',
						'7' => 'pink',
						'8' => 'default',
						'9' => 'indigo',
				);
				$total_not = 0;
				foreach ($result->result() as $menu2) {
					if ($menu2->site_url == 'divider') { ?>
						<h6 class="col-12 dropdown-header mb-1"><?= $menu2->menu; ?></h6>
						<?php
					} else { ?>
						<a href="<?php echo site_url($menu2->site_url); ?>" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-<?= $warna[rand(0, 9)]; ?>">
                      <i class="fa fa-<?= $menu2->icon; ?>"></i>
                    </span>
							<?php
							if ($menu2->notifikasi_query != '') {

								$query = str_replace(array(
										'{{request_dep}}'
								), array(
										(!$this->apl->super_user() and !$this->apl->tr() and !$this->apl->bm()) ? " AND tiket.id_dep='" . $this->session->id_dep . "' " : '',
								), $menu2->notifikasi_query);
								$total = $this->db->query($query)->row()->total;
								$total_not += $total;
								//echo '<span class="badge badge-circle bg-primary my-0 text-right">' . $total . '</span>';
								echo '<span class="bg-light badge badge-pill badge-primary ml-auto">' . $total . '</span>';
							}
							?>
							<small><?= $menu2->menu; ?></small>
						</a>
					<?php }
				} ?>

			</div>
		</div>


		<span class="badge badge-primary bg-neutral text-right mt--2"><?= $total_not; ?></span>
		<!--
		<span class="bg-secondary badge badge-pill badge-primary"><?= $total_not; ?></span>
		-->
		<!--
		<span class="badge badge-info badge-neutral mt-3"></span>
		-->
	</li>
<?php } ?>
