<?php

require ('fpdf.php');

if(isset($_GET['id_member'])){
    // get the values from 'create_barcode_image.php' sent by header(Location)
    $id = $_GET['id_member'];
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];
    
    $pdf = new FPDF();
    $pdf -> AddPage();
    $pdf -> SetFont('Arial', 'B', 16);


    $pdf->Image('../members_barcodes/' . $id . '.png', 50, 15, 50); 

    $name = $first_name . " " . $last_name; // name will be shown on the card with the barcode

    $pdf -> Cell(100, 70, $name, 1);
    $file_path = '../members_barcodes/' . $id . '.pdf';  // location to store pdf file
    $pdf -> Output($file_path, 'F'); // store pdf file in the chosen location
    //$pdf -> Output();
    unlink('../members_barcodes/' . $id . '.png');  // delete image.png, because pdf file has been created
    
    header('Location: ../index.php?id_member=' . $id . '&first_name=' . $first_name . '&last_name=' . $last_name); // send values to main page
}

