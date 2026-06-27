<?php
/**
 * Created by PhpStorm.
 * User: 2kangs
 * Date: 10/15/2019
 * Time: 6:36 PM
 */
?>
<?php
$controller = $this->uri->segment(1);

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
					   class="nav-link <?php echo ($tabs == 'profil') ? 'active' : ''; ?>"><i
							class="fa fa-user"></i> Profil</a>
				</li>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/unit/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link <?php echo ($tabs == 'unit') ? 'active' : ''; ?>"><i
							class="fa fa-building"></i> Unit</a>
				</li>
				<li class="nav-item active">
					<a href="<?= site_url($controller . '/edit/?' . $_SERVER['QUERY_STRING']); ?>"
					   class="nav-link bg-gradient-secondary"><i class="fa fa-edit"></i> Edit</a>
				</li>
			</ul>
		</div>
	</nav>

</div>

