<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/26/2015
 * Time: 10:53 PM
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Pesan
{

	function pesan_success($text)
	{
		$tampil = '<div class="alert-popup alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span> <strong>Success</strong>
                        <hr>
                        <p>' . $text . '</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>';
		$CI =& get_instance();
		$CI->load->library("session");
//        $CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;

	}

	function pesan_danger($text)
	{
		$tampil = '<div class="alert-popup alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <span class="fa fa-ok"></span> <strong>Bahaya </strong>
                        <hr>
                        <p>' . $text . '</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    
                    </div>';
		$CI =& get_instance();
		$CI->load->library("session");
//        $CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;
	}

	function pesan_info($text)
	{
		$tampil = '<div class="alert-popup alert alert-info">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span> <strong>Info</strong>
                        <hr>
                        <p>' . $text . '</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-info" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    
                    </div>';

		$CI =& get_instance();
		$CI->load->library("session");
		//$CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;
	}

	function pesan_warning($text)
	{
		$tampil = ' <div class="alert-popup alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span> <strong>Peringatan</strong>
                        <hr>
                        <p>' . $text . '</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-warning" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>';

		$CI =& get_instance();
		$CI->load->library("session");
		//     $CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;
	}

	function pesan_update()
	{
		$tampil = '<div class="alert-popup alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span> <strong>Success</strong>
                        <hr>
                        <p>successfully changed the data</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>';
		$CI =& get_instance();
		$CI->load->library("session");
//        $CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;
	}

	function pesan_add()
	{
		$tampil = '<div class="alert-popup alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span> <strong>Success</strong>
                        <hr>
                        <p>successfully added the data</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>';
		$CI =& get_instance();
		$CI->load->library("session");
//        $CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;

	}

	function pesan_delete()
	{
		$tampil = '<div class="alert-popup alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span class="fa fa-ok"></span> <strong>Success</strong>
                        <hr>
                        <p>successfully deleted data</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active progress-bar-success" role="progressbar"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>';
		$CI =& get_instance();
		$CI->load->library("session");
//        $CI->session->set_flashdata('psn', $tampil);
		$CI->session->psn = $tampil;

	}


	function pesan_tampil()
	{
		$CI =& get_instance();
		$CI->load->library("session");
		// echo $CI->session->psn;
		if ($CI->session->psn == null) {
			$CI->session->psn = '';
		}
		return json_encode(array('pesan' => $CI->session->psn));
		//$CI->session->psn = '';

	}

	/**
	 * function pesan_tampil_return()
	 * {
	 * $CI =& get_instance();
	 * $CI->load->library("session");
	 * return $CI->session->userdata('psn');
	 * $CI->session->psn = '';
	 * }
	 */
	function label_success($text)
	{
		return '<p class="alert alert-success">' . $text . ' </p>';
	}

	function label_danger($text)
	{
		return '<p class="alert alert-danger">' . $text . '</p>';
	}

	function label_info($text)
	{
		return '<p class="alert alert-info">' . $text . ' </p>';
	}

	function label_warning($text)
	{
		return '<p class="alert alert-warning">' . $text . '</p>';
	}
}
