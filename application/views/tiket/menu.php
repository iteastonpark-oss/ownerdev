<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 10/15/2019
 * Time: 6:36 PM
 */
?>
<?php
$controller = $this->uri->segment(1) . "/" . $this->uri->segment(2);

?>

<ul class="nav nav-pills  nav-fill">
	<li class="nav-item p-1">
		<a href="<?php echo site_url($controller . '/view/open'); ?>"
		   class="nav-link <?php echo ($tabs == 'open') ? 'bg-default' : ''; ?>"><i
					class="fa fa-folder-open"></i> Open
	
		</a>
	</li>
	<li class="nav-item p-1">
		<a href="<?php echo site_url($controller . '/view/proses'); ?>"
		   class="nav-link <?php echo ($tabs == 'proses') ? 'bg-default' : ''; ?>"><i
					class="fa fa-file"></i> Proses

		</a>
	</li>
	<li class="nav-item  p-1">
		<a href="<?php echo site_url($controller . '/view/done'); ?>"
		   class="nav-link <?php echo ($tabs == 'done') ? 'bg-default' : ''; ?>"><i
					class="fa fa-check"></i> Done
		</a>
	</li>
	<li class="nav-item p-1">
		<a href="<?php echo site_url($controller . '/view/close'); ?>"
		   class="nav-link <?php echo ($tabs == 'close') ? 'bg-default' : ''; ?>"><i
					class="fa fa-flag-o"></i> close
		</a>

	</li>


	<?php
	if ($tipe == '4') {
		?>
		<li class="nav-item active p-1">
			<a href="<?php echo site_url($controller . '/view/reject'); ?>"
			   class="btn btn-danger nav-link <?php echo ($tabs == 'reject') ? 'active' : ''; ?>"><i
						class="fa fa-remove"></i> Reject
			</a>

		</li>

	<?php } ?>

</ul>

<!--
<ul class="nav nav-pills nav-fill mb--3">
	<li class="nav-item">
		<a href="<?php echo site_url('request/page/' . $request . '?jt=2'); ?>"
		   class="btn nav-link <?php echo ($jatuh_tempo == '2') ? 'bg-info' : ''; ?>"><i
					class="fa fa-spinner"></i> Progress
			<span class="bg-light badge badge-md badge-circle badge-floating badge-primary border-white"><?= $tot->progress; ?></span>

		</a>

	</li>
	<li class="nav-item">
		<a href="<?php echo site_url('request/page/' . $request . '?jt=3'); ?>"
		   class="btn nav-link <?php echo ($jatuh_tempo == '3') ? 'bg-info' : ''; ?>"><i
					class="fa fa-warning"></i> Over due
			<span class="bg-light badge badge-md badge-circle badge-floating badge-primary border-white"><?= $tot->overdue; ?></span>

		</a>
	</li>
</ul>
-->
