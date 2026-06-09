<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->apl = new Apl();
	}


	function akses_notif($where)
	{

		$query = "SELECT `admin_menu`.`id`,`admin_menu`.`menu`,`admin_menu`.`site_url`
                FROM
                   `admin`
                      INNER JOIN `admin_level` ON `admin_level`.`id` = `admin`.`level`
                      INNER JOIN `admin_level_menu` ON
                        `admin_level`.`id` = `admin_level_menu`.`level_id`
                      INNER JOIN `admin_menu` ON `admin_level_menu`.`menu_id` = `admin_menu`.`id`
                                    WHERE 1";

		if ($where != '') {
			$query .= " AND `admin`.`id_admin` =" . $where;
		}
		return $this->db->query($query);
	}


	function akses_menu($where)
	{

		$query = "SELECT `admin_menu`.`id`,`admin_menu`.`menu`,`admin_menu`.`site_url`
                FROM
                   `admin`
                      INNER JOIN `admin_level` ON `admin_level`.`id` = `admin`.`level`
                      INNER JOIN `admin_level_menu` ON
                        `admin_level`.`id` = `admin_level_menu`.`level_id`
                      INNER JOIN `admin_menu` ON `admin_level_menu`.`menu_id` = `admin_menu`.`id`
                                    WHERE 1";

		if ($where != '') {
			$query .= " AND `admin`.`id_admin` =" . $where;
		}
		return $this->db->query($query);
	}

	function akses_tombol($where)
	{
		$query = "SELECT `admin_tombol`.`id`,`admin_tombol`.`kode`
                FROM
                  `admin`
                  INNER JOIN `admin_level` ON `admin_level`.`id` = `admin`.`level`
                  INNER JOIN `admin_level_tombol` ON 
                  `admin_level`.`id` = `admin_level_tombol`.`level_id`
                  INNER JOIN `admin_tombol` ON `admin_level_tombol`.`tombol_id` = `admin_tombol`.`id`
                                WHERE 1 ";

		if ($where != '') {
			$query .= " AND `admin`.`id_admin` =" . $where;
		}

		return $this->db->query($query);
	}

	public function cek_menu($idmenu, $menu)
	{

		error_reporting(E_NOTICE);
		$this->load->helper('array');


		/*
		if ($this->apl->administrator()) {
			$m = $this->apl->getAllData("admin_menu")->result();

			$menu = array();
			foreach ($m as $me) {
				$menu[] = $me->id;
			}
		} else {
			$m = $this->apl->getSelectedData("admin_level_menu"
				, array('level_id' => $this->session->level))->result();

			$menu = array();
			foreach ($m as $me) {
				$menu[] = $me->menu_id;
			}
		}
		*/
		if (!element($idmenu, $menu)) {
			$this->load->view('layout/error_404');
			die();
			//return show_404('application/views/layout/error_404.php', true);
			//return show_error('Tidak dapat mengkases Halama Ini', 401, 'Akeses User Aplikasi');

		}
	}

	public function cek_id_menu()
	{

		$modul = $this->apl->tampil_segment();
		if ($this->input->get('tab')) {
			$idmenu = $this->apl->get_nilai_pilih("admin_menu", "id", array('site_url' => $modul));
			if ($idmenu == '') {
				$idmenu = $this->apl->get_nilai_pilih("admin_menu", "id",
					array(
						'parent' => $idmenu,
						'site_url' => $this->input->get('tab')
					)
				);
			}

		} else {
			$idmenu = $this->apl->get_nilai_pilih("admin_menu", "id", array('site_url' => $modul));

		}


		if ($idmenu == '' || $idmenu == '0') {
			$parent = str_replace(site_url(), '', $_SERVER['HTTP_REFERER']);
			$parent = str_replace("?" . $_SERVER['QUERY_STRING'], '', $parent);
			$id = $this->apl->urut('admin_menu', 'id');
			$data = array(
				'id' => $id,
				'modul' => $modul,
				'menu' => $modul,
				'site_url' => $modul,
				'active' => 1,
				'status' => 'menu_hidden',
				'parent' => $this->apl->get_nilai_pilih('admin_menu', 'id', array('site_url' => $parent)),

			);

			$this->db->insert("admin_menu", $data);
			if ($this->apl->super_user()) {
				$_SESSION['menu'][$id] = $modul;
			}

		}


		return $idmenu;
	}

	function user_menu()
	{
		$idmenu = array();
		if ($this->session->menu) {
			foreach ($this->session->menu as $menuid => $menu) {
				$idmenu[] = $menuid;
			}
		}

		return $idmenu;
	}

	function user_tombol()
	{
		$idtombol = array();
		if ($this->session->tombol) {

			foreach ($this->session->tombol as $tombolid => $tombol) {
				$idtombol[] = $tombolid;
			}
		}
		return $idtombol;
	}

	function user_menu_active($user)
	{
		$idmenu = array();
		$result = $this->db->get_where("admin_akses", array('user_id' => $user))->result();
		foreach ($result as $menuid) {
			$idmenu[$menuid->menu_id] = $menuid->menu_id;

		}
		return $idmenu;
	}

	function user_tombol_active($user)
	{
		$idtombol = array();
		$result = $this->db->get_where("admin_tombol", array('user_id' => $user))->result();
		foreach ($result as $tombolid) {
			$idtombol[$tombolid->tombol_id] = $tombolid->tombol_id;

		}
		return $idtombol;
	}


	public function view_menu_menu($menu)
	{

		$this->db->select('*');
		$this->db->from('admin_menu');
		$this->db->where('parent', 0);
		$this->db->where_in('id', $menu);
		$this->db->order_by('urut');
		return $this->db->get()->result();
	}

	public function view_menu_notifikasi()
	{

		$idmenu = $this->user_menu();
		$this->db->select('*');
		$this->db->from('admin_menu');
		$this->db->where('notifikasi', 1);
		$this->db->where_in('id', $idmenu);
		$this->db->order_by('parent');
		return $this->db->get()->result();
	}


	public function view_menu_menu_parent($menu, $parent)
	{
		$this->db->select('*');
		$this->db->from('admin_menu');

		$this->db->where('parent', $parent);
		$this->db->where_in('id', $menu);
		$this->db->order_by('urut');
		return $this->db->get()->result();
	}

	public function view_tombol($tombol)
	{
		$this->db->select('*');
		$this->db->from('admin_tombol');
		$this->db->where_in('id', $tombol);
		return $this->db->get()->result();
	}

}

?>
