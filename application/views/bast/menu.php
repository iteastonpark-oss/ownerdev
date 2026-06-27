<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 10/15/2019
 * Time: 6:36 PM
 */
?>
<?php
$controller = $this->uri->segment(1) . '/' . $this->uri->segment(2);

?>
<div class="card-header">
	<nav class="navbar navbar-expand-lg  py-0 navbar-light  bg-white">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto nav nav-pills navbar-expand-lg">
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/detail/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'detail') ? 'active' : ''; ?>"><i
								class="fa fa-building-o"></i> Detail</a>

					<?php
					if($u->id_group=='1'){
					?>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/huni/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'huni') ? 'active' : ''; ?>"><i
								class="fa fa-user"></i> Residence Permit</a>
				</li>
				<?php }
					else {
					?>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/huni/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'huni') ? 'active' : ''; ?>"><i
								class="fa fa-user"></i> Commercial Area Tenants</a>
				</li>
				<?php } ?>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/surat/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'surat') ? 'active' : ''; ?>"><i
								class="fa fa-mail-forward"></i> Correspondence</a>
				</li>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/ganti_pemilik/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'pemilik') ? 'active' : ''; ?>"><i
								class="fa fa-refresh"></i> Change Owner</a>
				</li>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/berkas/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'berkas') ? 'active' : ''; ?>"><i
								class="fa fa-file"></i> File</a>
				</li>

				<!--
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/histori/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'histori') ? 'active' : ''; ?>"><i
							class="fa fa-history"></i> Histori</a>
				</li>
				-->
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/hapus/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'hapus') ? 'bg-danger' : 'bg-warning'; ?>"><i
								class="fa fa-file"></i> Delete</a>
				</li>

			</ul>
		</div>
	</nav>

</div>

