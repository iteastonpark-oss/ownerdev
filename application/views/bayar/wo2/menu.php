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
					<a href="<?php echo site_url($controller . '/bayar'); ?>"
					   class="nav-link <?php echo ($tabs == 'bayar') ? 'active' : ''; ?>"><i
							class="fa fa-bank"></i> Tagihan</a>
				</li>
				<li class="nav-item active">
					<a href="<?php echo site_url($controller . '/report'); ?>"
					   class="nav-link <?php echo ($tabs == 'report') ? 'active' : ''; ?>"><i
							class="fa fa-book"></i> Report</a>
				</li>
			</ul>


		</div>
	</nav>

</div>

