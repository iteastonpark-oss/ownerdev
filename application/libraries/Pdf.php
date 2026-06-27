<?php defined('BASEPATH') or exit('No direct script access allowed');

// Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
	public function __construct()
	{
		// require_once autoloader
		require_once dirname(__FILE__) . '/dompdf_1-0-2/autoload.inc.php';
		//require_once dirname(__FILE__) . '/dompdf/autoload.inc.php';
		$options = new Options();
		$options->setIsRemoteEnabled(true);
		$options->setIsJavascriptEnabled(true);
		$options->setIsHtml5ParserEnabled(true);
		$options->setIsFontSubsettingEnabled(true);
		$pdf = new Dompdf($options);
		//$pdf = new DOMPDF();
		$CI =& get_instance();
		$CI->dompdf = $pdf;
	}
}

?>
