<?php


/***********************************************************************************
Функция img_resize(): генерация thumbnails
Параметры:
  $src             - имя исходного файла
  $dest            - имя генерируемого файла
  $width, $height  - ширина и высота генерируемого изображения, в пикселях
Необязательные параметры:
  $quality         - качество генерируемого JPEG, по умолчанию - максимальное (100)
***********************************************************************************/
function img_resize($mode, $src, $dest, $width, $height, $quality=100)
{
  if (!file_exists($src)) return false;

  $size = getimagesize($src);

  if ($size === false) return false;

  // Определяем исходный формат по MIME-информации, предоставленной
  // функцией getimagesize, и выбираем соответствующую формату
  // imagecreatefrom-функцию.
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;
		
  switch ($mode) {
    case 'in':
	  $x_ratio = $width / $size[0];
	  $y_ratio = $height / $size[1];

	  $ratio       = min($x_ratio, $y_ratio);
	  $new_width   = floor($size[0] * $ratio);
	  $new_height  = floor($size[1] * $ratio);
	  $left_offset = 0;
	  $top_offset = 0;
	  break;
	case 'out':
	  $x_ratio = $size[0] / $width;
	  $y_ratio = $size[1] / $height;

	  $ratio       = min($x_ratio, $y_ratio);
	  $new_width   = floor($width * $ratio);
	  $new_height  = floor($height * $ratio);
	  
	  $left_offset = ($size[0] - $new_width) / 2;
	  $top_offset = ($size[1] - $new_height) / 2;
	  $resize = true;
	  break;
	case 'exact':
	  $left_offset = 0;
	  $top_offset = 0;
	  $new_width   = $width;
	  $new_height  = $height;
	  
  }
/*
  echo 'Ширина исходной картинки ' . $size[0] . '<br>';
  echo 'Высота исходной картинки ' . $size[1] . '<br>';
  echo 'Ширина шаблона ' . $width . '<br>';
  echo 'Высота шаблона ' . $height . '<br>';
  echo 'Коэффициент сжатия ' . $ratio . '<br>';
  echo 'Ширина результирующей картинки ' . $new_width . '<br>';
  echo 'Высота результирующей картинки ' . $new_height . '<br>';
  echo 'Координата X исходной картинки ' . $left_offset . '<br>';
  echo 'Координата X исходной картинки ' . $top_offset . '<br>';
*/

  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($new_width, $new_height);
  
  imagecopyresampled($idest, $isrc, 0, 0, $left, $top, 
  $new_width, $new_height, $size[0]-$left_offset*2, $size[1]-$top_offset*2);
  
  if ($resize) {
	$resized = imagecreatetruecolor($width, $height);
	imagecopyresampled($resized , $idest, 0, 0, 0, 0, $width, $height, $new_width, $new_height);
	imagejpeg($resized, $dest, $quality);
	imagedestroy($resized);
  }
  
  else imagejpeg($idest, $dest, $quality);

  imagedestroy($isrc);
  imagedestroy($idest);
  
  return true;

}
?>