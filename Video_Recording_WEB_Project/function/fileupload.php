


<?php

	if($_FILES['videofile']){
    $my_file = $_FILES['videofile'];
    $my_blob = file_get_contents($my_file['tmp_name']);
	$file_name='mir';
	date_default_timezone_set("Asia/Dhaka");
	$file_name=$file_name.'_'.date("M_D").'_'.date("h_i_sa");
    file_put_contents('uploads/'.$file_name.'.webm', $my_blob);
    }
?>

