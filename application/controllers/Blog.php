<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Blog extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Home_Model', 'home');
		$this->home = new Home_Model();

		$this->load->model('Web_Model', 'web');
		$this->web = new Web_Model();
		$this->load->model('Dropdown_Model', 'dropdown_model');
		$this->dropdown_model = new Dropdown_Model();
		$this->apl = new Apl();
		$this->pesan = new Pesan();
	}

	public function index()
	{
		$data['page'] = 'page/blog'; //Halaman di tampilkan
		$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
		$q = (isset($_GET['q'])) ? $_GET['q'] : '';
		$data['halaman'] = ($page == 0) ? '1' : $page;
		$limit = 6;
		$start = ($page > 1) ? ($page * $limit) - $limit : 0;

		$total = $this->web->getPost("1", "", "", "", $q)->num_rows();
		$data['pages'] = ceil($total / $limit);
		$data['post'] = $this->web->getPost("1", "", $limit, $start, $q)->result();
		$this->load->view('home', $data);
	}

	public function menu()
	{
		$data['page'] = 'page/blog_menu'; //Halaman di tampilkan
		$data['kategori'] = $this->web->getKategori()->result();
		$this->load->view('home', $data);
	}

	public function kategori($id_kategori)
	{
		if ($id_kategori == '9') {
			$tanggal = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
			$url = "https://bms.eprjatinangor.com/akun/laporan/in_out_owner?bulan=" . urlencode($tanggal) . '&header=0';
			$content = file_get_contents($url);
			$data['content'] = $content;

			$content = file_get_contents($url);
			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($content);
			libxml_clear_errors();
			$xpath = new DOMXPath($dom);
			$input = $xpath->query('//input[@id="tampil"]')->item(0);
			$tampil_value = $input ? $input->getAttribute('value') : null;
			$data['tampil'] = $tampil_value;

			$data['periode']=$tanggal;
			$data['visit'] = $this->web->getPostVisitPeriode($tanggal)->row()->jumlah;
			$data['page'] = 'page/blog-laporan'; //Halaman di tampilkan
			$this->load->view('home', $data);
		} else {
			$data['page'] = 'page/blog'; //Halaman di tampilkan
			$page = (isset($_GET['page'])) ? $_GET['page'] : 0;
			$q = (isset($_GET['q'])) ? $_GET['q'] : '';
			$data['halaman'] = ($page == 0) ? '1' : $page;
			$limit = 6;
			$start = ($page > 1) ? ($page * $limit) - $limit : 0;

			$total = $this->web->getPost("1", $id_kategori, "", "", $q)->num_rows();
			$data['pages'] = ceil($total / $limit);
			$data['post'] = $this->web->getPost("1", $id_kategori, $limit, $start, $q)->result();
			$this->load->view('home', $data);
		}
	}

	public function details($slug = "")
	{

		$id = $this->input->get('id');
		$data['page'] = 'page/blog-details'; //Halaman di tampilkan
		$data['post'] = $this->web->getPostDetail($id, $slug)->row();
		//$data['recent'] = $this->web->getPostRecent("1")->result();
		$data['visit'] = $this->web->getPostVisit($id)->row()->jumlah;

		$this->session->redirect = "blog/details/" . $slug . "?id=" . $id;

		$this->load->view('home', $data);
	}


}
