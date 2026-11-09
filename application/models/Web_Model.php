<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Web_Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->db2 = $this->load->database('database_kedua', TRUE);
	}

	public function getPost($status = "", $id_kategori = "", $limit = "9", $start = "0", $q = "")
	{
		$this->db2->select(
			'`thumb`,
			`judul`,
			`slug`,
			`tanggal`,
			`post`.`tm`,
			`post`.`thumb`,
			`post`.`id_kategori`,
			IFNULL(`kategori`.`nama`,"Tidak Berkategori") as kategori,
			');
		$this->db2->select('`post`.`banner`');
		$this->db2->select('id_post')
			->join('kategori', 'kategori.id_kategori=post.id_kategori')
			->where(array(
				'post.hapus' => 0,
				'kategori.tipe' => 1,
			));
		if ($status != "") {
			$this->db2->where(array('status' => $status));
		}
		if ($id_kategori != "") {
			$this->db2->where(array('post.id_kategori' => $id_kategori));
		}
		if ($q != "") {
			$this->db2->group_start();
			$this->db2->like('judul', $q);
			$this->db2->or_like('slug', $q);
			$this->db2->or_like('kategori.nama', $q);
			$this->db2->group_end();
		}
		if ($limit != "") {
			$this->db2->limit($limit, $start);
		}
		//$this->db2->limit(10,0);
		$this->db2->order_by('tm DESC');
		return $this->db2->from('post')->get();

	}

	/**
	 * Daftar kategori P3SRS tipe=1 untuk menu owner, siap tampil.
	 * total & terakhir hanya menghitung artikel terbit (hapus=0, status=1).
	 * Pemetaan label/deskripsi/ikon/urutan: config/p3srs_menu.php.
	 *
	 * @return array ['utama' => [obj...], 'dokumen' => [obj...]]
	 *   obj: id_kategori, nama (label tampil), nama_cms, deskripsi, ikon, jenis, total, terakhir (Y-m-d|null)
	 */
	public function getMenuP3srs()
	{
		$this->config->load('p3srs_menu', TRUE);
		$cfg = $this->config->item('p3srs_menu', 'p3srs_menu');

		$rows = $this->db2->select('kategori.id_kategori, kategori.nama, IFNULL(p.total,0) AS total, p.terakhir', FALSE)
			->from('kategori')
			->join('(SELECT id_kategori, COUNT(id_post) AS total, DATE(MAX(tanggal)) AS terakhir
				FROM post WHERE hapus = 0 AND status = 1 GROUP BY id_kategori) p',
				'p.id_kategori = kategori.id_kategori', 'left', FALSE)
			->where(array('kategori.tipe' => 1, 'kategori.hapus' => 0))
			->order_by('kategori.id_kategori', 'ASC')
			->get()->result();

		$byId = array();
		foreach ($rows as $r) {
			$byId[(int) $r->id_kategori] = $r;
		}

		$item = function ($id, $r, $def) use ($cfg) {
			$o = new stdClass();
			$o->id_kategori = $id;
			$o->nama_cms = $r->nama;
			$o->nama = $def ? $def['label'] : $r->nama;
			$o->deskripsi = $def ? $def['deskripsi'] : '';
			$o->ikon = $def ? $def['ikon'] : $cfg['ikon_default'];
			$o->jenis = ($id === 9) ? 'laporan' : 'artikel';
			$o->total = (int) $r->total;
			$o->terakhir = !empty($r->terakhir) ? $r->terakhir : NULL;
			return $o;
		};

		$out = array('utama' => array(), 'dokumen' => array());
		foreach ($cfg['utama'] as $id => $def) {
			if (isset($byId[$id])) {
				$out['utama'][] = $item($id, $byId[$id], $def);
				unset($byId[$id]);
			}
		}
		foreach ($cfg['dokumen'] as $id => $def) {
			if (isset($byId[$id])) {
				$out['dokumen'][] = $item($id, $byId[$id], $def);
				unset($byId[$id]);
			}
		}
		// Kategori baru di CMS yang belum dipetakan: tetap tampil, paling akhir di 'dokumen'
		foreach ($byId as $id => $r) {
			$out['dokumen'][] = $item($id, $r, NULL);
		}
		return $out;
	}

	public function getPostDetail($id = "", $slug = "")
	{
		$this->db2->select(
			'`thumb`,
			`judul`,
			`slug`,
			`tanggal`,
			`post`.`tm`,
			`post`.`body`,
			`post`.`banner`,
			`post`.`thumb`,
			`post`.`id_kategori`,
			IFNULL(`kategori`.`nama`,"Tidak Berkategori") as kategori,
			');
		$this->db2->select('id_post')
			->join('kategori', 'kategori.id_kategori=post.id_kategori')
			->where(array(
				'post.hapus' => 0,
				'kategori.tipe' => 1,
				'id_post' => $id,
				'slug' => $slug,
				'status' => 1,
			));


		return $this->db2->from('post')->get();

	}

	public function getPostVisit($id)
	{
		$tbl = $this->db2->select(
			'COUNT(1) as `jumlah`')
			->from('post_visit')
			->where(array(
				'id_post' => $id,
			))
			//->group_by('ip,browser')
			->group_by('id_bast')
			->get_compiled_select();


		$this->db2->select('COUNT(jumlah) as jumlah')
			->from('(' . $tbl . ') as tbl');
		return $this->db2->get();

	}

	public function getPostVisitPeriode($periode)
	{
		$tbl = $this->db2->select(
			'COUNT(1) as `jumlah`')
			->from('post_visit')
			->where(array(
				'periode' => $periode,
			))->group_by('id_bast')->get_compiled_select();


		$this->db2->select('COUNT(jumlah) as jumlah')
			->from('(' . $tbl . ') as tbl');
		return $this->db2->get();

	}

	public function insertVisit($data)
	{
		$this->db2->insert("post_visit", $data);
	}


}
