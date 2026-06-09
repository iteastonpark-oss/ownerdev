<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /*
    public function view()
    {

        $this->db->select('0 as No,`hrpegawai`.`nik`,`hrpegawai`.`nama`,`hrpegawai`.`email`,`hrpegawai`.`jabatan`,
                    `users`.`id`');
        $this->db->from('level');
        $this->db->join('users', '`users`.`level_id` = `level`.`id`', 'inner');
        $this->db->join('hrpegawai', '`hrpegawai`.`id` = `users`.`kry_id`', 'inner');
        if (!$this->apl->super_user()) {
            $this->db->where('`users`.`level_id`', $this->session->userdata('level'));
        }
        return $this->db->get();
    }
    */

    public function view_level()
    {

        $this->db->select('0 as No,`level`,`id` as Action ');
        $this->db->where('id !=', 0);
        $this->db->where('id !=', 100);
        if (!$this->apl->super_user()) {
            $this->db->where('`id`', $this->session->userdata('level'));
        }
        return $this->db->from('admin_level');
    }


}

?>
