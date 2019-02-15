
<html>
<head>

<style>
  
</style>
</head>
<body>

<?php
//	ini_set('display_errors', 1);
//	error_reporting(E_ALL ^ E_NOTICE);

#This code finds the most recent uploaded file and copies it to a single location for you to point to.

		# SET THIS TO THE DIRECTORY THAT CONTAIN YOUR CAMERAS ROOT FTP FOLDERS
		$rootDir = '/your-folder-structure-here/';
		
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

		$mostRecentFilePath = "";
		$mostRecentFileMTime = 0;
		$mostRecentFileName = "";
		$imageNameandPath = "";

		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootDir), RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($iterator as $fileinfo) 
		{
			if ($fileinfo->isFile()) 
			{
				if ($fileinfo->getMTime() > $mostRecentFileMTime) 
				{
					$mostRecentFileMTime = $fileinfo->getMTime();
					$mostRecentFilePath = $fileinfo->getPathname();
					$mostRecentFileName = $fileinfo->getFilename();
				}
			}
		}
		
		#SET THIS TO THE DIRECTORY AND THE FILE NAME THAT YOU WANT TO BE THE STATIC REFERENCE THAT YOU USE TO SEE THE MOST RECENT PHOTO
		
		$newfn = '/your-folder-structure-here/new-static-file-name.jpg';
		 
		 
		 
		if(copy($mostRecentFilePath,$newfn)){
//		 echo sprintf("%s was renamed to %s",$mostRecentFilePath,$newfn);
		}else{
//		 echo 'An error occurred during renaming the file';
		}
		
		

	
?>

</body>
</html>