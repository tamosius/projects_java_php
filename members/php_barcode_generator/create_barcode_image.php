<?php
// Including all required classes
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

// Including the barcode technology
require_once('class/BCGcode39.barcode.php');
//create_barcode_image(3000);

if(isset($_GET['id_member'])){  // receive values from 'display_members.php' directed by header line 68
    
    $id = $_GET['id_member'];
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];

    // Loading Font
    $font = new BCGFontFile('./font/Arial.ttf', 18);

    // Don't forget to sanitize user inputs
    //$text = isset($_GET['text']) ? $_GET['text'] : 'HELLO';

    // The arguments are R, G, B for color.
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $drawException = null;
    try {
        $code = new BCGcode39();
        $code->setScale(2); // Resolution
        $code->setThickness(30); // Thickness
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont($font); // Font (or 0)
        $code->parse($id); // members id
    } catch(Exception $exception) {
        $drawException = $exception;
    }

    /* Here is the list of the arguments
    1 - Filename (empty : display on screen)
    2 - Background color */
    $drawing = new BCGDrawing('../members_barcodes/'. $id . '.png', $color_white);
    if($drawException) {
        $drawing->drawException($drawException);
    } else {
        $drawing->setBarcode($code);
        $drawing->draw();
    }

    // Header that says it is an image (remove it if you save the barcode to a file)
    header('Content-Type: image/png');
    header('Content-Disposition: inline; filename="barcode.png"');
    header('Location: ../php_pdf_converter/convert_to_pdf.php?id_member=' . $id . '&first_name=' . $first_name . '&last_name=' . $last_name);

    // Draw (or save) the image into PNG format.
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
}

