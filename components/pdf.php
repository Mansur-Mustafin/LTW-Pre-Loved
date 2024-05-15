<?php

declare(strict_types=1);

function generatePdfBill(Item $item)
{
    $pdfContent = "%PDF-1.4\n";
    $pdfContent .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
    $pdfContent .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
    $pdfContent .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n";
    $pdfContent .= "4 0 obj\n<< /Length 44 >>\nstream\nBT /F1 24 Tf 50 750 Td (Transaction Bill) Tj ET\n";
    $y = 700;

    $pdfContent .= "BT /F1 16 Tf 50 {$y} Td (Name:  {$item->title} - {$item->price} $) Tj ET\n";

    $pdfContent .= "endstream\nendobj\n";
    $pdfContent .= "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
    $pdfContent .= "xref\n0 6\n0000000000 65535 f \n";
    $pdfContent .= "0000000010 00000 n \n";
    $pdfContent .= "0000000063 00000 n \n";
    $pdfContent .= "0000000111 00000 n \n";
    $pdfContent .= "0000000275 00000 n \n";
    $pdfContent .= "0000000434 00000 n \n";
    $pdfContent .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n495\n%%EOF";

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="prices.pdf"');

    echo $pdfContent;
}