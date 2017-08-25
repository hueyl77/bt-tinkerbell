<?php
require_once('./globals.php');

$barcode = _getVar($_REQUEST, 'barcode');
$bar_width = _getVar($_REQUEST, 'bar_width') ?: 2;
$bar_height = _getVar($_REQUEST, 'bar_height') ?: 30;

$output = generateBarcode($barcode, $bar_width, $bar_height);

$data = 'data:image/png;base64,' . $output;

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

file_put_contents(storage_path("barcodes/$barcode.png"), $data);

$image = imagecreatefrompng(storage_path("barcodes/$barcode.png"));
imagejpeg($image, storage_path("barcodes/$barcode.jpg"), 100);
?>
<img src="data:image/png;base64, <?php print $output ?>"
  alt="<?php print $barcode ?>" />