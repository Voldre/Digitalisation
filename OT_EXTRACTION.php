<?php

require("C:\wamp\www\Digitalisation-OT\\fpdf\\fpdf.php");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(190,50,"texte dans le cadre");

$pdf->Output();


?>
