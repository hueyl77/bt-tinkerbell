<?php
require_once('./globals.php');

$filename = _getVar($_REQUEST, 'filename');
$path = storage_path('/pdfs/' . $filename);

if (!file_exists($path)) {
    die("File not found");
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="'.$filename.'"');

readfile($path);
