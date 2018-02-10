## Tinkerbell

## Setup

### Install Homestead Vagrant

https://laravel.com/docs/5.5/homestead

### Install Dependencies

From your Homestead folder

```
$ vagrant ssh
$ cd /path/to/tinkerbell
$ composer install
```

Note: Because we're not using Milon Barcode generate with Laravel, we have to
manually add the following line to the top of:

vender/milon/barcode/src/Milon/Barcode/DNS1D.php

Under 'namespace' line

 require_once(__DIR__ . '/../../../../../../lib/globals.php');


Then find everywhere that says

```
  $this->setStorPath('milon.barcode');
```

and replace it with:

```
  $this->setStorPath(BARCODES_DIR_PATH);
```