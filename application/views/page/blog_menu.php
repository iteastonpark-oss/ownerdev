<div class="card">
	<div class="card-header"><h2>Menu Laporan P3SRS</h2></div>
	<div class="card-body">
		<ul class="list-group">
			<?php
			foreach ($kategori as $k) {
				echo '<a href="' . site_url('blog/kategori/' . $k->id_kategori) . '" class="m-2 btn-secondary"><li class="list-group-item">' . $k->nama
						//. '<span class="badge badge-secondary bg-success badge-pill text-right">' . $k->total . '</span>'
						. '</li></a>';
			}
			?>
		</ul>
		<ul class=" text-center">
	</div>
</div>
