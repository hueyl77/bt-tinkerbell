<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/pdfcrowd.php';

use Milon\Barcode\DNS1D;

session_start();

if (!isset($no_login) || !$no_login) {
    if (!$_SESSION["authenticated"]) {
        header('Location: /tinkerbell/');
    }
}


$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

// global constants
define('BARCODES_DIR_PATH', storage_path("barcodes"));

// delete any old files
deleteOldCsvs();
deleteOldPdfs();

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

function scrubFilename($filename) {
    $filename = preg_replace("/[\s_]/", "-", $filename);
    $filename = preg_replace('/[^0-9a-zA-Z-_.]/', '', $filename);

    return $filename;
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

    $event_name = scrubFilename($ticket_info['event_name']);
    $filename = sprintf('%s-%s_%s_%s_%s.pdf',
        $ticket_info['event_id'],
        substr($event_name, 0, 10),
        $ticket_info['section'],
        $ticket_info['row'],
        $ticket_info['seat']
    );

    $filename = scrubFilename($filename);
    $filepath = "$current_year/$current_month/" . $filename;
    file_put_contents($pdfs_directory_path . "/"  . $filepath, $pdf);

    return $filepath;
}

function renderTmTemplate($html, $data, $indexes)
{
    $fullname           = $data[$indexes['fullname']];
    $event_id           = $data[$indexes['event_id']];
    $event_name         = $data[$indexes['event_name']];
    $venue              = $data[$indexes['venue']];
    $event_date         = $data[$indexes['event_date']];
    $event_time         = $data[$indexes['event_time']];
    $section            = $data[$indexes['section']];
    $row                = $data[$indexes['row']];
    $seat               = $data[$indexes['seat']];
    $seat_notes         = $data[$indexes['seat_notes']];
    $barcode            = $data[$indexes['barcode']];
    $notes              = $data[$indexes['notes']];

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

    // barcode
    $barcode_content = generateBarcode($barcode);
    $barcode_src = "data:image/png;base64, " . $barcode_content;
    $html = str_replace('[BARCODE_SRC]', $barcode_src, $html);

    // optional params with generated random defaults
    $order_number       = $data[$indexes['order_number']] or generateOrderNumber();
    $ticket_date        = $data[$indexes['ticket_date']] or getTicketDate();
    $cc_code            = $data[$indexes['cc_code']] or generateCcCode();
    $zip_code           = $data[$indexes['zip_code']] or generateZipCode();
    $cc_zip             = $data[$indexes['cc_zip']]  or generateCcZip('VI');

    $html = str_replace('[ORDER]',          $order_number, $html);
    $html = str_replace('[TICKET_DATE]',    $ticket_date, $html);
    $html = str_replace('[CC_CODE]',        $cc_code, $html);
    $html = str_replace('[ZIP_CODE]',       $zip_code, $html);
    $html = str_replace('[CC_ZIP]',         $cc_zip, $html);

    // pdfcomment
    $pdf_comment = 'PDFCOMMENT{"source":"ticketfire",' .
        '"section":"' . $section. '",' .
        '"row":"' . $row . '",' .
        '"seat":"' . $seat. '",' .
        '"barcode":"' . $barcode. '"}';

    $html = str_replace('[PDF_COMMENT]', $pdf_comment, $html);

    return $html;
}

function deleteOldPdfs()
{
    $pdfs_directory = storage_path("pdfs");
    deleteOldFilesInDir($pdfs_directory);

    $zips_directory = storage_path("zips");
    deleteOldFilesInDir($zips_directory);
}

function deleteOldCsvs()
{
    $csvs_directory = storage_path("csvs");
    deleteOldFilesInDir($csvs_directory);
}

function deleteOldFilesInDir($dir) {
    /*** cycle through all files in the directory ***/
    $objects = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($dir),
        \RecursiveIteratorIterator::SELF_FIRST);

    foreach($objects as $name => $object) {
        if ($name != '.' && $name != '..') {
            if(is_file($name) && ((time() - filectime($name)) > 3600)) {
                unlink($name);
            }
        }
    }
}

function randomLetters($num_chars) {
    $letters = "";
    for($i=0; $i<$num_chars; $i++) {
        $letters .= chr(64+rand(0,26));
    }

    return $letters;
}

function randomNumber($digits) {
    return rand(pow(10, $digits-1), pow(10, $digits)-1);
}

function generateBarcode($barcode, $bar_width = 2, $bar_height = 30)
{
    $barcode = preg_replace('/\s+/', '', $barcode);
    $output = DNS1D::getBarcodePNG($barcode, "I25", $bar_width, $bar_height);

    return $output;
}

function generateOrderNumber()
{
    $random_order_number = randomNumber(2) . "-" . randomNumber(4);
    return $random_order_number;
}

function getTicketDate()
{
    return strtoupper(date('dMy'));
}

function generateCcCode()
{
    return "VI " . rand(1, 399) . "X";
}
function generateCcZip($cc_chars = 'VI')
{
    return $cc_chars . rand(100, 499) . "ZIP";
}

function generateZipCode()
{
    return "ZIP" . randomNumber(4);
}

function formatBarcodeNumbers($barcode)
{
    $barcode = strval($barcode);

    $formatted = "";
    for($i=0; $i<strlen($barcode); $i++) {
        if ($i > 0 && $i % 4 == 0) {
            $formatted .= " ";
        }
        $formatted .= $barcode[$i];
    }
    return $formatted;
}
