<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/pdfcrowd.php';

session_start();

if (!isset($no_login) || !$no_login) {
    if (!$_SESSION["authenticated"]) {
        header('Location: /tinkerbell/');
    }
}


$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
$dotenv->load();

// global constants
define('BARCODES_DIR_PATH', storage_path("barcodes"));


// global funcs
function _getVar($array, $key) {
  if (isset($array[$key])) {
    return $array[$key];
  }
  return "";
}

function storage_path($folder) {
  return __DIR__ . "/../storage/$folder";
}

function generatePdf($content, $ticket_info)
{
    // create an API client instance
    $pdfcrowd_user = getenv('PDFCROWD_USER');
    $pdfcrowd_key = getenv('PDFCROWD_KEY');

    $client = new Pdfcrowd($pdfcrowd_user, $pdfcrowd_key);
    $client->setInitialPdfZoomType(\Pdfcrowd::FIT_WIDTH);
    $client->setPdfScalingFactor(2.0);

    //$client->setPageWidth("8.5in");
    //client->setPageHeight("11.0in");

    $pdf_margin_top = '0.25in';
    $pdf_margin_right = '0.25in';
    $pdf_margin_bottom = '0.25in';
    $pdf_margin_left = '0.25in';

    $client->setPageMargins($pdf_margin_top,
        $pdf_margin_right, $pdf_margin_bottom, $pdf_margin_left);

    // convert a web page and store the generated PDF into a $pdf variable
    $pdf = $client->convertHtml($content);

    // save to folder
    $current_year = date("Y");
    $current_month = date('m');

    $pdfs_directory_path = storage_path("pdfs");
    if (!file_exists("$pdfs_directory_path/$current_year")) {
        mkdir("$pdfs_directory_path/$current_year");
    }

    if (!file_exists("$pdfs_directory_path/$current_year/$current_month")) {
        mkdir("$pdfs_directory_path/$current_year/$current_month");
    }

    if (!isset($ticket_info['event_name'])) {
        $ticket_info['event_name'] = $ticket_info['event_id'];
    }

    $filename = sprintf('%s_%s_%s_%s.pdf',
        substr($ticket_info['event_name'], 0, 10),
        $ticket_info['section'],
        $ticket_info['row'],
        $ticket_info['seat']
    );

    $file_name = preg_replace( '/[^a-z0-9 .-]+/', '-', strtolower($filename));

    $filepath = "$current_year/$current_month/" . $filename;
    file_put_contents($pdfs_directory_path . "/"  . $filepath, $pdf);

    return $filepath;
}