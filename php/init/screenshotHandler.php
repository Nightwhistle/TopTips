<?php

require_once 'init.php';

$data = $_POST['imgBase64'];
$fileName = $_POST['fileName'];
$fileName = substr($fileName, 1);
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);

$uploadURL = TICKETS_SCREENSHOT_FOLDER;
$filePath = $uploadURL . $fileName . '.png';
var_dump($filePath);
file_put_contents($filePath, $data);

