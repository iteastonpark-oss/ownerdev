<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/19/2016
 * Time: 10:28 AM
 */
?>
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Upload_Model extends CI_Model
{

	var $upload = '/upload/'; //Folder Utama
	var $name = 'EPR-BMS'; //Folder Utama

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function fileName($fileName)
	{
		$explode = explode('.', $fileName);
		$extensi = $explode[count($explode) - 1];
		$rnd = rand(000, 999);
		$fileName = str_replace($extensi, "", $fileName);
		$fileName = preg_replace("/[^A-Za-z0-9_]/", "", $fileName) . "." . $extensi;
		$fileName = $this->name . $rnd . "-" . $fileName;

		return $fileName;
	}

	public function fileNameDokumen($fileName)
	{
		$explode = explode('.', $fileName);
		$extensi = $explode[count($explode) - 1];
		$rnd = rand(000, 999);
		$fileName = str_replace($extensi, "", $fileName) . $rnd . "." . $extensi;
		//$fileName = preg_replace("/[^A-Za-z0-9_]/", "", $fileName) .$rnd. "." . $extensi;
		$fileName = $fileName;

		return $fileName;
	}
	public function fileNameDokumenWA($fileName)
	{
		$explode = explode('.', $fileName);
		$extensi = $explode[count($explode) - 1];
		//$rnd = rand(000, 999);
		$fileName = str_replace($extensi, "", $fileName) . "." . $extensi;
		//$fileName = preg_replace("/[^A-Za-z0-9_]/", "", $fileName) .$rnd. "." . $extensi;
		$fileName = $fileName;

		return $fileName;
	}

	public function targetFile($tempFile, $fileName, $target)
	{
		if (!empty($_FILES)) {

			$targetPath = getcwd() . $this->upload . $target . '/';
			$targetFile = $targetPath . $fileName;
			move_uploaded_file($tempFile, $targetFile);
		}
		return $targetFile;

	}


	public function upload($tempFile, $targetFile, $table, $data)
	{

		if (!empty($_FILES)) {
			move_uploaded_file($tempFile, $targetFile);
			$this->db->insert($table, $data);
		}
	}

	public function upload_resize($targetFile)
	{
		/**
		 * Resize
		 */

		$config['image_library'] = 'gd2';
		$config['source_image'] = $targetFile;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 75;
		$config['height'] = 50;
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
		$this->image_lib->clear();

	}

	public function upload_watermak($targetFile)
	{

		/**
		 * Watermaking
		 */
		$wm['source_image'] = $targetFile;
		$wm['wm_text'] = $this->name;
		$wm['wm_type'] = 'text';
		$wm['wm_font_path'] = './system/fonts/texb.ttf';
		$wm['wm_font_size'] = '30';
		$wm['wm_font_color'] = 'ffffff';
		$wm['wm_vrt_alignment'] = 'middle';
		$wm['wm_hor_alignment'] = 'center';
		$wm['wm_padding'] = '5';
		$this->load->library('image_lib', $wm);
		$this->image_lib->initialize($wm);
		$this->image_lib->watermark();
		$this->image_lib->clear();

	}

	public function upload_watermak_gambar($targetFile)
	{

		/**
		 * Watermaking
		 */

		//$wm['image_library'] = 'GD2';
		$wm['source_image'] = $targetFile;
		$wm['quality'] = '10%';
		$wm['wm_type'] = 'overlay';
		$wm['wm_overlay_path'] = $this->upload . '/icon_2kangs.png';
		$wm['wm_padding'] = '0';
		$wm['wm_opacity'] = 50;
		$wm['wm_vrt_alignment'] = 'top';
		$wm['wm_hor_alignment'] = 'right';

		$this->load->library('image_lib', $wm);
		$this->image_lib->initialize($wm);
		$this->image_lib->watermark();
		$this->image_lib->clear();

	}


	public function tampil_gambar_modal($gambar, $alt, $target_file, $atribut = 'width="120" height="120"')
	{
		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;
		$tampil_gambar = '';
		if ($gambar != '') {
			if (read_file("." . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar .= '<a href="#" class="clik-gambar"><img class="img-rounded" src="' . $cek_gambar . '"
                    src_besar="' . $cek_gambar . '" alt="' . $alt . '" ' . $atribut . '> </a>';
			} else {
				$tampil_gambar .= '<a href="#" class="clik-gambar"><img class="img-rounded"
                    src="' . base_url() . $this->upload . 'no_pict.png' . '"
                    src_besar="' . base_url() . $this->upload . 'no_pict.png' . '" alt="' . $alt . '" ' . $atribut . '> </a>';
			}
		} else {
			$tampil_gambar .= '<a href="#" class="clik-gambar"><img class="img-rounded"
                    src="' . base_url() . $this->upload . 'no_pict.png' . '"
                    src_besar="' . base_url() . $this->upload . 'no_pict.png' . '" alt="' . $alt . '"' . $atribut . '> </a>';
		}

		$tampil_gambar .= "<script>
            $(function () {
                $('.clik-gambar').on('click', function (e) {
					e.preventDefault();
                    $('.imagepreview').attr('src', $(this).find('img').attr('src_besar'));
                    $('#imagemodal').modal('show');
                });
            });
        </script>";

		$tampil_gambar .= '<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-gambar">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <img src="" class="imagepreview" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>';
		return $tampil_gambar;

	}

	public function href_gambar($gambar, $alt, $target_file, $atribut = 'width="120" height="120"')
	{
		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;
		$tampil_gambar = '';
		if ($gambar != '') {
			if (file_exists(getcwd() . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar .= '<a href="' . $cek_gambar . '" class="clik-gambar" 
                target="_blank" ' . $atribut . '>' . $gambar . '</a>';
			} else {
				$tampil_gambar .= 'Tidak Ada Gambar';
			}
		} else {

			$tampil_gambar .= 'Tidak Ada Gambar';
		}

		return $tampil_gambar;

	}

	public function tampil_gambar($gambar, $alt, $target_file, $style = 'width="120" height="120"')
	{
		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;
		//echo $cek_gambar;
		$tampil_gambar = '';
		if ($gambar != '') {
			if (file_exists(getcwd() . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar = '<a href="#" class="clik-gambar">
                    <img class="img-rounded text-center" src="' . $cek_gambar . '"
                    src_besar="' . $cek_gambar . '" alt="' . $alt . '" ' . $style . '> </a>';
			} else {
				$tampil_gambar = '<a href="#" class="clik-gambar">
            <img class="img-rounded text-center"
                    src="' . $this->upload . 'no_pict.png"
                    src_besar="' . $this->upload . 'no_pict.png" alt="' . $alt . '" ' . $style . '> </a>';
			}
		} else {
			$tampil_gambar = '<a href="#" class="clik-gambar">
<img class="img-rounded text-center"
                    src="' . $this->upload . 'no_pict.png"
                    src_besar="../2file/no_pict.png" 
                    alt="' . $alt . '" ' . $style . '> </a>';
		}

		return $tampil_gambar;
	}

	public function tampil_gambar_new_tab($gambar, $alt, $target_file, $style = 'width="120" height="120"')
	{
		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;
		if ($gambar != '') {
			if (file_exists(getcwd() . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar = '<a href="' . $cek_gambar . '" class="" target="_blank">
                    <img class="img-rounded img-thumbnail text-center" src="' . $cek_gambar . '"
                    src_besar="' . $cek_gambar . '" alt="' . $alt . '" ' . $style . '> </a>';
			} else {
				$tampil_gambar = '<a href="' . $cek_gambar . '" class="clik-gambar" target="_blank">
            <img class="img-rounded img-thumbnail text-center"
                    src="' . $this->upload . 'no_pict.png"
                    src_besar="' . $this->upload . 'no_pict.png" alt="' . $alt . '" ' . $style . '> </a>';
			}
		} else {
			$tampil_gambar = '<a href="' . $cek_gambar . '" class="clik-gambar" target="_blank">
<img class="img-rounded img-thumbnail text-center"
                    src="' . $this->upload . 'no_pict.png"
                    src_besar="../2file/no_pict.png" 
                    alt="' . $alt . '" ' . $style . '> </a>';
		}
		return $tampil_gambar;
	}

	public function tampil_gambar_basic($gambar, $alt, $target_file, $style = '')
	{
		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;
		if ($gambar != '') {
			if (file_exists(getcwd() . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar = '<img alt="' . $alt . '" src="' . $cek_gambar . '" ' . $style . '>';
			} else {
				$tampil_gambar = '<img src="' . base_url() . $this->upload . 'no_pict.png' . '" 
                alt="' . $alt . '" ' . $style . '>';
			}
		} else {
			$tampil_gambar = '<img  src="' . base_url() . $this->upload . 'no_pict.png' . '"
                    alt="' . $alt . '" ' . $style . '>';
		}

		return $tampil_gambar;
	}

	public function tampil_gambar_priview($gambar, $alt, $target_file, $style = '')
	{
		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;
		if ($gambar != '') {
			if (file_exists(getcwd() . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar = '<img id="priview" alt="' . $alt . '" src="' . $cek_gambar . '" ' . $style . '>';
			} else {
				$tampil_gambar = '<img id="priview" src="' . base_url() . $this->upload . 'no_pict.png' . '" 
                alt="' . $alt . '" ' . $style . '>';
			}
		} else {
			$tampil_gambar = '<img  id="priview" src="' . base_url() . $this->upload . 'no_pict.png' . '"
                    alt="' . $alt . '" ' . $style . '>';
		}

		return $tampil_gambar;
	}


	public function link($gambar, $target_file)
	{

		$cek_gambar = base_url() . $this->upload . $target_file . '/' . $gambar;

		if ($gambar != '') {
			if (file_exists(getcwd() . $this->upload . $target_file . '/' . $gambar)) {
				$tampil_gambar = $cek_gambar;
			} else {
				$tampil_gambar = base_url() . $this->upload . '/no_pict.png';
			}
		} else {
			$tampil_gambar = base_url() . $this->upload . '/no_pict.png';
		}
		return $tampil_gambar;
	}

	public function link_web($gambar, $target_file)
	{

		$cek_gambar = 'https://eprjatinangor.com/' . $this->upload . $target_file . '/' . $gambar;
		if (@getimagesize($cek_gambar)) {
			
				$tampil_gambar = $cek_gambar;
			} else {
				$tampil_gambar = base_url() . $this->upload . '/no_pict.png';
			}
		return $tampil_gambar;
	}

	public function signature_qr($gambar, $atr = 'width="120px" height="120px"')
	{
		if (read_file(base_url() . $gambar)) {
			return '<br><img src="' . base_url($gambar) . '" ' . $atr . '><br>';
		} else {
			return "<br><br><br><br><br><br><br><br>";
		}
	}

	public function cek_link($gambar, $target_file)
	{
		if ($gambar != '') {
			if (read_file("." . $this->upload . $target_file . '/' . $gambar)) {
				return "1";
			} else {
				return "0";
			}
		} else {
			return "0";
		}
	}

	public function cek_link_url($gambar)
	{
		if ($gambar != '') {
			if (read_file("." . $gambar)) {
				return "1";
			} else {
				return "0";
			}
		} else {
			return "0";
		}
	}
}
