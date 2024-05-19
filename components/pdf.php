<?php

declare(strict_types=1);

function generatePdfBill(Item $item, array $transaction)
{
    // Read the QR code image file
    $qrCodeImagePath = '../assets/img/qr-code.png';
    $qrCodeImage = file_get_contents($qrCodeImagePath);
    $qrCodeImageInfo = getimagesize($qrCodeImagePath);

    // Image width and height
    $qrCodeImageWidth = $qrCodeImageInfo[0];
    $qrCodeImageHeight = $qrCodeImageInfo[1];

    $pdfContent = "%PDF-1.4\n";
    $pdfContent .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
    $pdfContent .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
    $pdfContent .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> /XObject << /Im1 6 0 R >> >> >>\nendobj\n";
    $pdfContent .= "4 0 obj\n<< /Length 700 >>\nstream\n";
    $pdfContent .= "BT /F1 24 Tf 50 750 Td (Shipping Form) Tj ET\n";
    $pdfContent .= "BT /F1 22 Tf 50 720 Td (Matador OLX) Tj ET\n";

    $y = 650;
    $pdfContent .= "BT /F1 16 Tf 50 $y Td (Transaction Details) Tj ET\n";
    $y -= 30;
    $pdfContent .= "BT /F1 14 Tf 50 $y Td (Date: " . date('Y-m-d') . ") Tj ET\n";
    $y -= 30;
    $pdfContent .= "BT /F1 14 Tf 50 $y Td (Transaction ID: {$transaction['id']}) Tj ET\n";
    
    $y -= 40;
    $pdfContent .= "BT /F1 16 Tf 50 $y Td (Product Details) Tj ET\n";
    $y -= 30;
    $pdfContent .= "BT /F1 14 Tf 50 $y Td (Name: {$item->title}) Tj ET\n";

    $y -= 480;
    $pdfContent .= "BT /F1 16 Tf 440 $y Td (Total price: {$item->price} $) Tj ET\n";
    
    // QR code position
    $pdfContent .= "q\n";
    $pdfContent .= "100 0 0 100 500 100 cm\n";  // Adjust position and size as needed
    $pdfContent .= "/Im1 Do\n";
    $pdfContent .= "Q\n";

    $pdfContent .= "endstream\nendobj\n";
    $pdfContent .= "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
    $pdfContent .= "6 0 obj\n<< /Type /XObject /Subtype /Image /Width {$qrCodeImageWidth} /Height {$qrCodeImageHeight} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen($qrCodeImage) . " >>\nstream\n";
    $pdfContent .= $qrCodeImage . "\n";
    $pdfContent .= "endstream\nendobj\n";

    
    $pdfContent .= "xref\n0 7\n0000000000 65535 f \n";
    $pdfContent .= "0000000010 00000 n \n";
    $pdfContent .= "0000000063 00000 n \n";
    $pdfContent .= "0000000111 00000 n \n";
    $pdfContent .= "0000000275 00000 n \n";
    $pdfContent .= "0000000434 00000 n \n";
    $pdfContent .= "0000000506 00000 n \n";
    $pdfContent .= "trailer\n<< /Size 7 /Root 1 0 R >>\nstartxref\n650\n%%EOF";

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="shipping_form.pdf"');

    echo $pdfContent;
}
