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
  //echo var_dump(getimagesize('original.jpg'));
?>