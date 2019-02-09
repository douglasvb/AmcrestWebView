<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL ^ E_NOTICE);

	$name = $_GET['name'];
	$dir = $_GET['dir'];
    $mimes = array
    (
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpg',
        'gif' => 'image/gif',
        'png' => 'image/png'
    );

	$fileNameParts = explode('.', $file);
    $ext = strtolower(end($fileNameParts));

    $file = $dir . '/' . $name;
    header('content-type: '. $mimes[$ext]);
    header('content-disposition: inline; filename="'.$name.'";');
    readfile($file);
?>