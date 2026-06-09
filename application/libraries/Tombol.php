<?php

/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 8/26/2015
 * Time: 10:53 PM
 */

defined('BASEPATH') or exit('No direct script access allowed');

class Tombol
{

	function __construct()
	{
		$CI =& get_instance();
		$this->db = $CI->load->database('default', TRUE);
	}

	public function get_tambah_js($click,$text="Add")
	{
		$tombol = '<button class="btn btn-neutral"
                    onclick="' . $click . '">
                    <i class="fa fa-plus"></i> '.$text.'</button>';
		return $tombol;
	}

	public function get_hapus_js($click, $text = 'Delete')
	{
		$tombol = '<a class="btn btn-danger btn-sm " 
                    href="javascript:void()"
                    title="Hapus" onclick="' . $click . '">
                    <i class="fa fa-trash"></i> ' . $text . '</a>';
		return $tombol;
	}

	public function get_hapus_js_dropdown($click, $text = 'Delete')
	{
		$tombol = '<a class="dropdown-item" 
                    href="javascript:void()"
                    title="Hapus" onclick="' . $click . '">
                    <i class="fa fa-trash"></i> ' . $text . '</a>';
		return $tombol;
	}

	public function get_edit_js($click,$text = 'Edit')
	{
		$tombol = '<a class="btn btn-sm  btn-primary" 
                    href="javascript:void()"
                    title="Edit" onclick="' . $click . '">
                    <i class="fa fa-pencil"></i> '.$text.'</a>';
		return $tombol;
	}

	public function get_edit_js_dropdown($click)
	{
		$tombol = '<a class="dropdown-item" 
                    href="javascript:void()"
                    title="Edit" onclick="' . $click . '">
                    <i class="fa fa-pencil"></i> Edit</a>';
		return $tombol;
	}

	public function get_simpan_js($click_save, $click_batal)
	{
		$tombol = '<div class="btn-group pull-right btn-sm">';
		$tombol .= '<button type="submit" id="btnSave" onclick="' . $click_save . '" 
                    data-dismiss="modal" class="btn btn-primary"></button>
                    <button type="button" id="btnClose" class="btn  btn-danger" onclick="' . $click_batal . '" 
                    data-dismiss="modal"> Cancel</button>';
		$tombol .= '</div>';
		return $tombol;
	}

	public function get_simpan_post($click)
	{
		$tombol = '<div class="btn-group pull-right btn-sm">';
		$tombol .= '<button type="submit" id="btnSave" 
                    data-dismiss="modal" class="btn btn-sm btn-primary"></button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"> Cancel</button>';
		$tombol .= '</div>';
		return $tombol;
	}

	public function get_tambah_url($url)
	{
		$tombol = '<a class="btn btn-neutral pull-right"
                    href="' . $url . '">
                    <i class="fa fa-plus"></i> Add</a>';
		return $tombol;
	}

	public function get_hapus_url($url)
	{
		$tombol = '<a class="btn btn-sm btn-danger" 
                    href="' . $url . '">
                    title="Hapus">
                    <i class="fa fa-trash"></i> Delete</a>';
		return $tombol;
	}

	public function get_edit_url($url)
	{
		$tombol = '<a class="btn btn-sm btn-primary" href="' . $url . '"
                        title="Edit">
                        <i class="fa fa-pencil"></i> Edit</a>';
		return $tombol;
	}

	public function get_detail_url($url)
	{
		$tombol = '<a class="btn btn-sm btn-info" href="' . $url . '"
                        title="Edit">
                        <i class="fa fa-search"></i> Detail</a>';
		return $tombol;
	}

	public function get_detail_url_dropdown($url)
	{
		$tombol = '<a class="dropdown-item" href="' . $url . '"
                        title="Edit">
                        <i class="fa fa-search"></i> Detail</a>';
		return $tombol;
	}

	function get_save($nama = "Simpan")
	{
		$tombol = '<div class="btn-group pull-right btn-sm ">
                      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> ' . $nama . '</button>
                      <button type="reset" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</button>
                       </div>';
		return $tombol;
	}

	function get_button_umum($url, $name, $atribut)
	{
		$tombol = '<a href="' . $url . '" ' . $atribut . '>' . $name . '</a>';
		return $tombol;
	}
}
