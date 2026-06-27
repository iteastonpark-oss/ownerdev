<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 3:00 PM
 */
?>

<?php
$controller = strtolower($this->uri->segment(1));
?>
<div class="row">
	<div class="col-xl-3 col-6 mb-2">
		<a href="<?= site_url($controller . '/utility/view/air'); ?>">
			<div class="card card-stats mb-4 mb-xl-0">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<h5 class="card-title text-uppercase text-muted mb-0">Water</h5>
							<span class="h2 font-weight-bold mb-0"></span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape bg-danger text-white rounded-circle shadow">
								<i class="fas fa-water"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>


</div>
