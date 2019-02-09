
<html>
<head>
<style>

  ul.images {
	margin: 0;
	padding: 0;
	white-space: nowrap;
	width: 100%;
	overflow-x: auto;
	background-color: #ddd;
  }

  ul.images li {
	display: inline;
	width: 150px;
	height: 150px;
  }
</style>
</head>
<body>

<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL ^ E_NOTICE);
	
	# SET THIS TO THE DIRECTORY THAT CONTAIN YOUR CAMERAS ROOT FTP FOLDERS
	$rootDir = '/media/ssd/';
	
	$fileDisplayTypes = array('jpg', 'jpeg', 'png', 'gif');
	
	$ignoreFolders = array('.', '..');

	$selectedDir = $_GET['dir'];
	// Strip slashes
	$selectedDir =  explode('/', $selectedDir)[0];
	$selectedDir =  explode('\\', $selectedDir)[0];

	$dateDir = $_GET['date'];
	// Strip slashes
	$dateDir =  explode('/', $dateDir)[0];
	$dateDir =  explode('\\', $dateDir)[0];

	// Main page, show camera options
	if ($selectedDir == NULL)
	{
		$rootDirContents = scandir($rootDir);

		foreach ($rootDirContents as $cameraDir)
		{
			if ($cameraDir !== '.' && $cameraDir !== '..' && $cameraDir !== 'System Volume Information')     
			{
				echo '<h2><a href=view.php?dir=' . $cameraDir . '>' . $cameraDir . '</a></h2>';
			}
		}
	}
	// Show images for the selected day
	else if ($dateDir != NULL)
	{
		$selectedDirContents = scandir($rootDir . $selectedDir);
		$cameraDir = '/' . $selectedDirContents[2];

		$indoorCam = false;

		$hoursFolder = $rootDir . $selectedDir . $cameraDir . '/' . $dateDir . '/001/jpg/';
		if (file_exists($hoursFolder) == false)
		{
			$indoorCam = true;
			$hoursFolder = $rootDir . $selectedDir . $cameraDir . '/' . $dateDir;
		}

		$hourDirs = array_diff(scandir($hoursFolder), $ignoreFolders);

		echo '<h2>' . $selectedDir . ' - ' . $dateDir . '</h2>';

		foreach (array_reverse($hourDirs) as $hourDir)
		{
			if ($indoorCam)
			{
				# THIS ALTERNATE FOLDER STRUCTURE FOR MY INDOOR CAMERA DOESNT DISPLAY CORRECTLY YET
				
				$imagePath = $rootDir . $selectedDir . $cameraDir . '/' . $dateDir . '/' . $hourDir . '/jpg/';
				if (file_exists($imagePath))
				{
					$imagesFiles = array_diff(scandir($imagePath), $ignoreFolders);
				}
				else
				{
					// No JPGs saved
					$imagesFiles = array();
				}
				$minDirs = array('00');
			}
			else
			{
				$imagePath = $rootDir . $selectedDir . $cameraDir . '/' . $dateDir . '/001/jpg/' . $hourDir;
				$minDirs = array_diff(scandir($imagePath), $ignoreFolders);
			}

			foreach (array_reverse($minDirs) as $minDir)
			{
				if (file_exists($imagePath . '/' . $minDir) == true)
				{
					// Find all the minute folders
					$fullImagePath = $imagePath . '/' . $minDir;
					$imagesFiles = array_diff(scandir($fullImagePath), $ignoreFolders);
				}
				else
				{
					$fullImagePath = $imagePath;
				}
				

				echo '<h3>' . $hourDir . ':' . $minDir . '</h3>';
				echo '<ul class="images">';
				foreach ($imagesFiles as $imageFile)
				{
					$fileNameParts = explode('.', $imageFile);
					$fileType = strtolower(end($fileNameParts));
					if ($imageFile !== '.' && $imageFile !== '..' && in_array($fileType, $fileDisplayTypes) == true)     
					{
						echo "<li><a href='img.php?name={$imageFile}&dir={$fullImagePath}'>";
						echo "<img src='img.php?name={$imageFile}&dir={$fullImagePath}' height=\"360\" width=\"640\"/>";
						echo "</a></li>";
					}
				}
				echo '</ul>';
			}
		}
	}
	// Show a cameras root page with dates to pick from
	else if ($selectedDir != NULL)
	{
		$selectedDirContents = scandir($rootDir . $selectedDir);
		$dateDirContents = scandir($rootDir . $selectedDir . '/' . $selectedDirContents[2]);

		foreach (array_reverse($dateDirContents) as $dateFolder)
		{
			if ($dateFolder !== '.' && $dateFolder !== '..' && $dateFolder !== 'DVRWorkDirectory')     
			{
				echo '<h2><a href=view.php?dir=' . $selectedDir . '&date=' . $dateFolder . '>' . $dateFolder . '</a></h2>';
			}
		}
	}

?>

</body>
</html>
