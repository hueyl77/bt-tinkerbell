<?php
require_once('./globals.php');

use Milon\Barcode\DNS1D;

$barcode = _getVar($_REQUEST, 'barcode');
$barcode = preg_replace('/\s+/', '', $barcode);

$bar_width = _getVar($_REQUEST, 'bar_width') ?: 2;
$bar_height = _getVar($_REQUEST, 'bar_height') ?: 33;

$output = DNS1D::getBarcodePNG($barcode, "I25", $bar_width, $bar_height);

echo $output;