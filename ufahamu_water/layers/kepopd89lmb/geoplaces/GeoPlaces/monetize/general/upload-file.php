<?php
$file = dirname(__FILE__);
$file = substr($file,0,stripos($file, "wp-content"));
require($file . "/wp-load.php");
$uploaddir = TEMPLATEPATH."/images/tmp/";
$nam = $_FILES['uploadfile']['name'];
$upload = '';
$file_size= $_FILES['uploadfile']['size'];
$limit_size = get_option('ptthemes_max_image_size');
if(!$limit_size){
$limit_size = 50000;
update_option('ptthemes_max_image_size',$limit_size);
}
if($file_size[0] >= $limit_size){
		echo 'LIMIT';
		exit;
	}
if(count($nam) > 10)
  {
    echo count($nam);
	die;
  }
foreach($nam as $key=>$_nam)
   {
	 $path_info = pathinfo($_nam);
	 $file_extension = $path_info["extension"];
	 $finalName = basename($_nam,".$file_extension")."-".time().".".$file_extension;
	 $file = $uploaddir .$finalName ;

	 if (move_uploaded_file($_FILES['uploadfile']['tmp_name'][$key], $file))
	 {
		echo $upload = $finalName.",";
		//echo "success";
	 }	 else
	 {
		echo "error";
	 }
   }
?>