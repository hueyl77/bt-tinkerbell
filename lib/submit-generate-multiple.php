<?php

require_once('./globals.php');

$csv_file = _getVar($_REQUEST, 'csv-file');
if (!$csv_file) {
    die("Invalid csv_file");
}

$row_indexs_to_import = _getVar($_REQUEST, 'row_checkboxes');
if (empty($row_indexs_to_import)) {
    header("Location: ../multiple-imported.php?csv-file=" . $csv_file);
}

$csv_filepath = storage_path("csvs/") . $csv_file;

if (file_exists($csv_filepath)) {

    $pdfs_to_zip = [];

    $template_path = __DIR__ . "/../tm-ticket-template.php";
    $html_template = file_get_contents($template_path);

    $row_index = 0;
    $indexes = [];
    $event_id = "event_id";
    $event_name = "event_name";

    if (($handle = fopen($csv_filepath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
            $num = count($data);
            if ($row_index == 0) {
                for ($c=0; $c < $num; $c++) {
                    $indexes[$data[$c]] = $c;
                }
                $row_index++; continue; // skip first line (labels)
            }

            if (!in_array($row_index, $row_indexs_to_import)) {
                $row_index++;
                continue;
            }

            $event_id = $data[$indexes['event_id']];
            $event_name = $data[$indexes['event_name']];
            $content = renderTmTemplate($html_template, $data, $indexes);
            $ticket_info = [
                'event_id'      => $event_id,
                'event_name'    => $event_name,
                'section'       => $data[$indexes['section']],
                'row'           => $data[$indexes['row']],
                'seat'          => $data[$indexes['seat']]
            ];

            $pdf_path = generatePdf($content, $ticket_info);
            array_push($pdfs_to_zip, storage_path("pdfs/") . $pdf_path);

            $row_index++;
        }
        fclose($handle);
    }
}

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
