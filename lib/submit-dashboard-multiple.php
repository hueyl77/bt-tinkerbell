<?php

require_once('./globals.php');

$fullname         = _getVar($_REQUEST, 'fullname');
$order_number     = _getVar($_REQUEST, 'order_number');
$section          = _getVar($_REQUEST, 'section');
$row              = _getVar($_REQUEST, 'row');

$seats = [];
$barcodes = [];

$max_num_seatrows = 9;
for($i=1; $i<=$max_num_seatrows; $i++) {
  $seats[$i] = _getVar($_REQUEST, 'seat' . $i);
  $barcodes[$i] = _getVar($_REQUEST, 'barcode' . $i);
}

$zip_code     = _getVar($_REQUEST, 'zip_code');
$cc_code      = _getVar($_REQUEST, 'cc_code');
$cc_zip       = _getVar($_REQUEST, 'cc_zip');
$event_id     = _getVar($_REQUEST, 'event_id');
$event_date   = _getVar($_REQUEST, 'event_date');
$venue        = _getVar($_REQUEST, 'venue');

$ticket_date  = _getVar($_REQUEST, 'ticket_date');
$seat_notes   = _getVar($_REQUEST, 'seat_notes');
$event_name   = _getVar($_REQUEST, 'event_name');
$event_time   = _getVar($_REQUEST, 'event_time');
$notes        = _getVar($_REQUEST, 'notes');

$pdfs_to_zip = [];

$template_path = __DIR__ . "/../tm-ticket-template.php";
$html_template = file_get_contents($template_path);


for($i=1; $i<=$max_num_seatrows; $i++) {
  if (!empty($seats[$i]) && !empty($barcodes[$i])) {
    $html = $html_template;
    $seat = $seats[$i];
    $barcode = $barcodes[$i];

    $html = str_replace('[NAME]',           $fullname, $html);
    $html = str_replace('[EVENT_ID]',       $event_id, $html);
    $html = str_replace('[EVENT_NAME]',     $event_name, $html);
    $html = str_replace('[SECT]',           $section, $html);
    $html = str_replace('[ROW]',            $row, $html);
    $html = str_replace('[SEAT]',           $seat, $html);
    $html = str_replace('[SEAT_NOTES]',     $seat_notes, $html);
    $html = str_replace('[VENUE]',          $venue, $html);
    $html = str_replace('[NOTES]',          $notes, $html);
    $html = str_replace('[DATE]',           $event_date, $html);
    $html = str_replace('[TIME]',           $event_time, $html);
    $html = str_replace('[BARCODE]',        formatBarcodeNumbers($barcode), $html);
    $html = str_replace('[ORDER]',          $order_number, $html);
    $html = str_replace('[TICKET_DATE]',    $ticket_date, $html);
    $html = str_replace('[CC_CODE]',        $cc_code, $html);
    $html = str_replace('[ZIP_CODE]',       $zip_code, $html);
    $html = str_replace('[CC_ZIP]',         $cc_zip, $html);

    // barcode
    $barcode_content = generateBarcode($barcode);
    $storage_url = getenv('STORAGE_URL');
    $barcode_src = $storage_url . "barcodes/" . $barcode . ".jpg";
    $html = str_replace('[BARCODE_SRC]', $barcode_src, $html);

    // pdfcomment
    $pdf_comment = 'PDFCOMMENT{"source":"ticketfire",' .
        '"section":"' . $section. '",' .
        '"row":"' . $row . '",' .
        '"seat":"' . $seat. '",' .
        '"barcode":"' . $barcode. '"}';

    $html = str_replace('[PDF_COMMENT]', $pdf_comment, $html);

    $ticket_info = [
        'event_id'      => $event_id,
        'event_name'    => $event_name,
        'section'       => $section,
        'row'           => $row,
        'seat'          => $seat
    ];

    $pdf_path = generatePdf($html, $ticket_info);
    array_push($pdfs_to_zip, storage_path("pdfs/") . $pdf_path);
  }
}

// output zip file
$event_name = scrubFilename($event_name);
$filename = sprintf('%s-%s',
    $event_id,
    substr($event_name, 0, 10)
);
$filename = scrubFilename($filename);
$zip_filepath = storage_path("zips/$filename-" . time() . ".zip");

$zipper = new Chumper\Zipper\Zipper;
$zipper->make($zip_filepath)->add($pdfs_to_zip)->close();

$filename = basename($zip_filepath);

// http headers for zip downloads
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Length: " . filesize($zip_filepath));

ob_end_flush();
readfile($zip_filepath);
