<?php
require_once('./globals.php');

$barcode = _getVar($_REQUEST, 'barcode');
$bar_width = _getVar($_REQUEST, 'bar_width') ?: 2;
$bar_height = _getVar($_REQUEST, 'bar_height') ?: 30;

$output = generateBarcode($barcode, $bar_width, $bar_height);
?>
<img src="data:image/png; base64, <?php print $output ?>"
  alt="<?php print $barcode ?>" style="border: 1px solid red;" />