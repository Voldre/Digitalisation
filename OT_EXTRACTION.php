<?php

session_start(); // Toujours nécessaire !

ob_start();

    require("header_css.php"); // Une fois

    require("OT_1_content.php");
    echo "---------------------------------------------------------------------------------------";
    echo "---------------------------------------------------------------------------------------";
    echo "-----------------------------------------------------------------------------<br/><br/>";
    require("OT_2_content.php");
    echo "---------------------------------------------------------------------------------------";
    echo "---------------------------------------------------------------------------------------";
    echo "-----------------------------------------------------------------------------<br/><br/>";
    require("OT_3_content.php");
    echo "---------------------------------------------------------------------------------------";
    echo "---------------------------------------------------------------------------------------";
    echo "-----------------------------------------------------------------------------<br/><br/>";
    require("OT_4_content.php");

$content = ob_get_clean();


//echo htmlspecialchars($content);
echo $content;
        // ON ENREGISTRE L'OT AU FORMAT HTML
file_put_contents("OT-".$_SESSION['OT_ID'].".html", $content);


$content2 = file_get_contents("Test_1.html");

$stylesheet = file_get_contents('style.css');

/* // Dompdf
$filename = "test";

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$dompdf->loadHtml($content2);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream($filename);
*/









/*
require("C:\wamp\www\Digitalisation-OT\\fpdf\\html2pdf.php");

$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);

$pdf->ignore_invalid_utf8 = true;

$pdf->WriteHTML($stylesheet,1);
$pdf->WriteHTML($content2);

$pdf->Output();
//$pdf->Output("myPDF.pdf","D");

*/


/*
// Include the main TCPDF library (search for installation path).
require_once('C:\wamp\www\Digitalisation-OT\tcpdf_min\tcpdf_import.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print

// Print text using writeHTMLCell()
$pdf->writeHTML($content2, true, false, true, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');
*/


?>
