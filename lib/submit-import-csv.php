<?php
require_once('./globals.php');

$csv_file = _getVar($_FILES, 'csv_file');

if (!$csv_file || count($csv_file) == 0) {
  die("invalid csv file");
}

/*if ($csv_file['type'] != 'text/csv') {
  die("Invalid file type.  Please select a text/csv file.");
}*/

$directory_path = storage_path("csvs");
$filename = $csv_file['name'];
$filename = preg_replace('/\s+/', '', $filename);
$filename = substr($filename, 0, strrpos($filename, '.') - 1);
$filename .= "-" . time() . ".csv";

$current_year = date("Y");
$current_month = date('m');

if (!file_exists("$directory_path/$current_year")) {
    mkdir("$directory_path/$current_year");
}

if (!file_exists("$directory_path/$current_year/$current_month")) {
    mkdir("$directory_path/$current_year/$current_month");
}

$target = "$directory_path/$current_year/$current_month/$filename";

move_uploaded_file( $csv_file['tmp_name'], $target);

header("Location: ../multiple-imported.php?csv-file=$current_year/$current_month/$filename");
