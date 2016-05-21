<?php
require dirname(__FILE__).'/../init.php';

use Addons\OverSea\Common\OSSHelper;

//$object = "yzphoto/1/test.txt";
//$object = "yzphoto/1/";
$command = $_GET['c'];
$object = $_GET['o'];
if ($command == 'put'){
    $content='qq';
    $options = array();
    OSSHelper::putObject($object, $content,$options);
} else if ($command == 'del'){
    OSSHelper::deleteObject($object);
}


//OSSHelper::doesBucketExist();
//OSSHelper::putObject();