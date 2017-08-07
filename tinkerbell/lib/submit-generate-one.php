<?php

require_once('./globals.php');

$event_id = _getVar($_REQUEST, 'event_id');
$content =  _getVar($_REQUEST, 'pdf_content');

$html = $content;

try
{
    $ticket_info = [];
    $ticket_info['event_name'] = _getVar($_REQUEST, 'event_name') or $event_id;
    $ticket_info['section'] = _getVar($_REQUEST, 'section');
    $ticket_info['row'] = _getVar($_REQUEST, 'row');
    $ticket_info['seat'] = _getVar($_REQUEST, 'seat');

    $filepath = generatePdf($html, $ticket_info);
    echo $filepath;
}
catch(PdfcrowdException $why)
{
    echo "Pdfcrowd Error: " . $why;
}