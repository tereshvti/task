<?php


/***********************************************************************************
������� img_resize(): ��������� thumbnails
���������:
  $src             - ��� ��������� �����
  $dest            - ��� ������������� �����
  $width, $height  - ������ � ������ ������������� �����������, � ��������
�������������� ���������:
  $quality         - �������� ������������� JPEG, �� ��������� - ������������ (100)
***********************************************************************************/
function img_resize($mode, $src, $dest, $width, $height, $quality=100)
{
  if (!file_exists($src)) return false;

  $size = getimagesize($src);

  if ($size === false) return false;

  // ���������� �������� ������ �� MIME-����������, ���������������
  // �������� getimagesize, � �������� ��������������� �������
  // imagecreatefrom-�������.
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
  echo '������ �������� �������� ' . $size[0] . '<br>';
  echo '������ �������� �������� ' . $size[1] . '<br>';
  echo '������ ������� ' . $width . '<br>';
  echo '������ ������� ' . $height . '<br>';
  echo '����������� ������ ' . $ratio . '<br>';
  echo '������ �������������� �������� ' . $new_width . '<br>';
  echo '������ �������������� �������� ' . $new_height . '<br>';
  echo '���������� X �������� �������� ' . $left_offset . '<br>';
  echo '���������� X �������� �������� ' . $top_offset . '<br>';
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