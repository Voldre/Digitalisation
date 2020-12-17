
<?php
    $result = array();
    $imagedata = base64_decode(file_get_contents('php://input'));
    $filename = md5(date("dmYhisA"));
    //Location to where you want to created sign image
    $file_name = './img/'.$filename.'.png';
    file_put_contents($file_name,$imagedata);
    $result['status'] = 1;
    $result['file_name'] = $file_name;
    echo json_encode($result);
?>