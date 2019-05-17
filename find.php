<?php

#This code finds the most recent uploaded file and shows it.

include_once('amcrest_config.php');

/* code below here */

echo "<html>
<head>
<style>
	.imgbox {
		display: grid;
		height: 100%;
	}
	.center-fit {
		max-width: 100%;
		max-height: 100vh;
		margin: auto;
	}
</style>
</style>
</head>
<body>";


if ($debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}


$ignoreFolders = array('.', '..');

$selectedDir = $rootDir;
// Strip slashes
$selectedDir = explode('/', $selectedDir)[0];
$selectedDir = explode('\\', $selectedDir)[0];

$dateDir = $_GET['date'];
// Strip slashes
$dateDir = explode('/', $dateDir)[0];
$dateDir = explode('\\', $dateDir)[0];

$mostRecentFilePath = "";
$mostRecentFileMTime = 0;
$mostRecentFileName = "";
$imageNameandPath = "";


$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootDir), RecursiveIteratorIterator::CHILD_FIRST);
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isFile()) {
        if ($fileinfo->getMTime() > $mostRecentFileMTime) {
            $lastFilePath = $mostRecentFilePath;
            $mostRecentFileMTime = $fileinfo->getMTime();
            $mostRecentFilePath = $fileinfo->getPathname();
            $mostRecentFileName = $fileinfo->getFilename();
        }
    }
}

$ext = pathinfo($mostRecentFileName, PATHINFO_EXTENSION);


function display_image($mostRecentFilePath)
{
    $imageNameandPath = str_replace("/home/forkedmeadow/forkedmeadow/", "", $mostRecentFilePath);
    echo "<br><br><div class=\'imgbox\'>";
    echo sprintf('<a href="%s"target="_blank"><center><img class="center-fit" src="%s" alt="Nearly Live View.  Probably."></center></a></div>', $imageNameandPath, $imageNameandPath);
}

if (in_array($ext, $fileDisplayTypes)) {
    if ($debug) {
        echo "The file type is correct.<br>";
        echo sprintf('The file type is %s <br>', $ext);
    }

    $waitlongenough = time() - filemtime($mostRecentFilePath);
    if ($waitlongenough > $howLongToWait) {
        if ($debug) {
            echo sprintf('The image is at least %s seconds old.<br><br>', $howLongToWait);
        }
        display_image($mostRecentFilePath);

    } else {
        if ($debug) {
            echo "The most recent image still hasn't finished uploading so here is the last image.<br><br>";
        }
        display_image($lastFilePath);
    }

} elseif ($ext == 'php') {
    if ($debug) {
        echo "<br><br>Wait a bit for the latest image to be uploaded.  The PHP file is still the newest.<br><br>";
    }
} elseif ($ext == '' && $ext != 'php') {
    if ($debug) {
        echo "<br><br>The camera appears to have a live connection to the internet but has not uploaded an image since its last time checking in with the server.  This is likely caused by a slow internet connection or latency.  Both of these issues are likely because of a problem with the ISP.<br><br>";
        echo "The last time the camera contacted the server was ";
        echo date('m/d/Y H:i:s', $mostRecentFileMTime);
        echo "<br><br>";
        if (strpos($mostRecentFilePath, 'DVRWorkDirectory') !== false) {
            echo "The most recent file name is DVRWorkDirectory -- This confirms that the camera can see the internet but is having trouble uploading an image. <br><br>";
        } else {
            echo "Something else is going on.  There is a new file in the directory structure that is not recognized.";
        }
        //echo sprintf('The most recent file name is %s <br><br>', $mostRecentFilePath);


    }
} else {
        echo "<br><br>An error occured while determining the file type <br><br>";
        echo sprintf('We had an error... the file extension is %s <br><br>', $ext);
}

echo "</body>
</html>";

?>

