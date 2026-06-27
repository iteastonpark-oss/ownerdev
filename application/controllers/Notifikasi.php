<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_Model', 'login_model');
        $this->apl = new Apl();
        $this->pesan = new Pesan();
        $this->login_model = new Login_Model();
    }


    public function pesan()
    {
        echo $this->pesan->pesan_tampil();
        $this->session->psn = '';

    }

    public function status_bast($status)
    {
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'proses';
        $list = $this->inv_unit->getDataUnitStatus($status, $tab);
        echo json_encode(array('total' => $list->num_rows()));//output to json format
    }

    /*
    public function notifikasi_nav()
    {
        $data['notif'] = $this->login_model->view_menu_notifikasi();
        $this->load->view('layout/notifikasi_nav', $data);
    }
    */
	public function notifikasi_nav()
	{
		//$data['notif'] = $this->login_model->view_menu_notifikasi();
		$this->load->view('layout/notifikasi_nav');
	}
}

?>
