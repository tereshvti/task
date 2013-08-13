<?php
class Modes
{
  const IN        = 0;
  const OUT       = 1;
  const EXACT     = 2;
}

  require ('imgresize.php');
  if (img_resize('in', 'original.jpg', 'small.jpg', 150, 80))
    echo 'Image resized OK';
  else
    echo 'Resize failed!';
  echo '<br>';
  
  $test = 'test2';
  $dir = getcwd() . "\\images\\cache\\" . $test;
  if (!file_exists($dir)) {
    echo '<br> директории нет, надо бы создать';
	mkdir($dir);
  }
  
  
  function listFolderFiles($dir){
    $ffs = scandir($dir);
    echo '<ol>';
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..' && is_dir($ff)){
            echo '<li>'.$ff;
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            echo '</li>';
        }
    }
    echo '</ol>';
  }
  function is_empty_dir($dir){
    echo $dir . '<br>';
    $ffs = scandir($dir);
	$result = array();
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..' && is_dir($ff)){
			array_push($result, $ff);
			echo '<br>' . $ff;
        }
    }
	echo var_dump($result) . '<br>';
	return $result;
  }
  is_empty_dir(getcwd() . "\\images\\cache\\");
  //echo var_dump(is_empty_dir($dir));
  //echo var_dump(getimagesize('original.jpg'));
?>