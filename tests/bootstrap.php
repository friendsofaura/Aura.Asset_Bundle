<?php
// autoloader
$file = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($file)) {
    require $file;
} else {
    echo "Install the dependencies via composer";
}
