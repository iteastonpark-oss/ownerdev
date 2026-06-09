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

	public function getKategori()
	{
		/*
		$this->db2->select('*')
			->where(array(
				'tipe'=>1,
				'hapus'=>0,
			));
		*/
		$this->db2->select(' kategori.`id_kategori`,kategori.nama, IFNULL(`post`.`total`,0) as total')
			//->from('kategori')
			->join('(SELECT COUNT(`id_post`) AS total, `id_kategori`
    FROM
      `post`
    WHERE
      `hapus` = 0 GROUP BY id_kategori) post', '`post`.`id_kategori` = `kategori`.`id_kategori`', 'left');

		$this->db2->where(array('tipe' => '1', 'hapus' => 0));

		return $this->db2->from('kategori')->get();

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
