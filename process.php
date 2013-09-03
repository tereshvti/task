<?php
  require ('imgresize.php');
  
  $link = mysql_connect("localhost", "root", "")
        or die("Could not connect: " . mysql_error());
  mysql_select_db('test') or die('There is no DB \'test\'.');
  $result = mysql_query("SELECT * FROM presets WHERE name = \"" . $_GET['preset-name'] . "\"")
    or die("Invalid query: " . mysql_error());
  mysql_close($link);  

  $row = mysql_fetch_array($result) or die("No such preset \"" . $_GET['preset-name'] . "\" in database");
  $filename = $_GET['filename'];
  $mode = $row['mode'];
  $width = $row['width'];
  $height = $row['height'];
	
  $destname = "\\images\\cache\\" . $_GET['preset-name'] . "\\" . $filename;
  $filename = "origins\\" . $filename;
  if (file_exists(".\\" . $filename)) {
	img_resize($mode, $filename, $destname, $width, $height);
	header('Refresh: 3; URL=http://localhost/task/images/cache/' . $_GET['preset-name'] . "/" . $_GET['filename']);
	echo 'Wait 3 sec to get image link.';
	exit;
  }
  else echo $filename . " - original file doesn't exist. <br>";
  
?>