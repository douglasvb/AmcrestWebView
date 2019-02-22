<html>
<head>
</head>
<body>

<?php

#This code finds the most recent uploaded file and copies it to a single location for you to point to.

#CONFIGURATION INFORMATION
# SET THIS TO THE DIRECTORY THAT CONTAIN YOUR CAMERAS ROOT FTP FOLDERS
$rootDir = '/your-folder-structure-here/';


# PUT THE EXTENSIONS OF THE FILES YOU CARE ABOUT HERE
$fileDisplayTypes = array('jpg', 'jpeg', 'png', 'gif');

# SET THIS TO THE DIRECTORY AND THE FILE NAME THAT YOU WANT TO BE THE STATIC REFERENCE THAT YOU USE TO SEE THE MOST RECENT PHOTO
$newfn = '/your-folder-structure-here/camera1.jpg';

# SET THE LOGFILE NAME HERE
$logFile = '/your-folder-structure-here/log.txt';

# DETERMINE HOW LONG TO WAIT BEFORE THE NEXT IMAGE IS SERVED
$waitTime = 60;

# TURN ON AND OFF DEBUG
$debug = False;

/* code below here */

if ($debug) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}


$ignoreFolders = array('.', '..');

$selectedDir = $rootDir;//$_GET['dir'];
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
            $mostRecentFileMTime = $fileinfo->getMTime();
            $mostRecentFilePath = $fileinfo->getPathname();
            $mostRecentFileName = $fileinfo->getFilename();
        }
    }
}

function getTimeRan($logFile,$debug)
{
    if ($debug) {
        if (file_exists($logFile)) {
            echo "The file $logFile exists<br><br>";
        } else {
            echo "The file $logFile does not exist<br><br>";
        }
    }
    $fh = fopen($logFile, 'r+') or die ("Unable to open log file!");
    $time = fgets($fh);
    fclose($fh);
    return (int)$time;
}


$ext = pathinfo($mostRecentFileName, PATHINFO_EXTENSION);


if (in_array($ext, $fileDisplayTypes)) {
    $lastTimeRan = getTimeRan($logFile,$debug);
    $delayTime = $lastTimeRan + $waitTime;
    $currentTime = (int)time();
    if ($debug) {
        echo "the current time is ";
        echo $currentTime;
        echo "<br>";
        echo sprintf('the last time the image was updated is %s <br>', $lastTimeRan);
        echo sprintf('The delayed time is %s <br>', $delayTime);
        echo "the file type is correct<br>";
        echo sprintf('the $ext is %s <br>', $ext);
        echo sprintf('the difference between the current time and our delayed time is %s<br>', $currentTime - $delayTime);
        echo sprintf('the difference between the current time and our last saved image is %s<br>', $currentTime - $lastTimeRan);
    }
    $doWeProceed = $currentTime - $delayTime;
    if ($debug) {
        echo sprintf('the amount of time to proceed is %s <br>',$doWeProceed);
    }
    if ($doWeProceed > 0) {
        if (copy($mostRecentFilePath, $newfn)) {
            $time_ran = time();
            $fh = fopen($logFile, 'w+');
            fwrite($fh, $time_ran);
            fclose($fh);
            if ($debug) {
                echo sprintf('%s <br> was renamed to <br>%s <br><br>', $mostRecentFilePath, $newfn);
                echo sprintf('the time that was saved in the file is %s<br><br>', $time_ran);
            }
        } else {
            if($debug) {
                echo sprintf('An error occurred during renaming the file.<br>The most recent file path is: %s <br>The new filename is %s <br><br>', $mostRecentFilePath, $newfn);
            }
        }
    } elseif ($doWeProceed <= 0) {
        if($debug) {
            echo "We need to wait a little longer for the latest image to come from the server. <br><br>";
        }
    } else {
        if($debug) {
            echo "An error occured with identifying the time.<br><br>";
        }
    }

} elseif ($ext == 'php') {
    if($debug) {
        echo "<br><br>Wait a bit for the latest image to be uploaded.  The PHP file is still the newest.<br><br>";
    }
} else {
    if($debug) {
        echo "<br><br>An error occured while determining the file type <br><br>";
        echo sprintf('We had an error... the $ext is %s <br><br>', $ext);
    }
}

if($debug) {
    echo "Made it to the end";
    echo "<br><br>";
    echo "<img src='/camera1.jpg' alt='Nearly Live View.  Probably.'>";
}

?>

</body>
</html>