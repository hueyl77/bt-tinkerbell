<?php

require_once('./globals.php');

$fullname     = _getVar($_REQUEST, 'fullname');
$order_number = _getVar($_REQUEST, 'order_number');
$event_id     = _getVar($_REQUEST, 'event_id');
$event_name   = _getVar($_REQUEST, 'event_name');
$event_date   = _getVar($_REQUEST, 'event_date');
$event_time   = _getVar($_REQUEST, 'event_time');
$venue        = _getVar($_REQUEST, 'venue');
$section      = _getVar($_REQUEST, 'section');
$row          = _getVar($_REQUEST, 'row');
$seat         = _getVar($_REQUEST, 'seat');
$seat_notes   = _getVar($_REQUEST, 'seat_notes');
$barcode      = _getVar($_REQUEST, 'barcode');
$zip_code     = _getVar($_REQUEST, 'zip_code');
$cc_code      = _getVar($_REQUEST, 'cc_code');
$ticket_date  = _getVar($_REQUEST, 'ticket_date');
$notes        = _getVar($_REQUEST, 'notes');

$filename = sprintf('%s-%s.csv',
    $event_id,
    substr($event_name, 0, 10)
);
$filename = scrubFilename($filename);

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment;filename={$filename}");

$headers = ["event_id", "event_name", "venue",
  "section", "row", "seat", "seat_notes",
  "event_date", "event_time", "barcode",
  "fullname", "order_number", "ticket_date",
  "cc_code", "zip_code", "notes"];

$data = [
  $headers,
  [
    $event_id, $event_name, $venue,
    $section, $row, $seat, $seat_notes,
    $event_date, $event_time, $barcode,
    $fullname, $order_number, $ticket_date,
    $cc_code, $zip_code, $notes
  ],
];

$file = fopen('php://output', 'w');
foreach ($data as $row)
{
    fputcsv($file, $row);
}

// Close the file
fclose($file);
