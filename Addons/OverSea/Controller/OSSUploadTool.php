<?php
//[ This is just for test, not use now ]

require dirname(__FILE__).'/../init.php';

use Addons\OverSea\Common\OSSHelper;

//$object = "yzphoto/1/test.txt";
//$object = "yzphoto/1/";
$command = $_GET['c'];
echo $command;
$object = $_GET['o'];
echo $object;
if ($command == 'put'){
    $content='qq';
    $options = array();
    OSSHelper::putObject($object, $content,$options);
} else if ($command == 'del'){
    OSSHelper::deleteObject($object);
} else if ($command == 'list') {
    OSSHelper::listObjects($object);
} else if ($command == 'listdelete') {
    //http://www.clcentury.com/weiphp/Addons/OverSea/Controller/OSSUploadTool.php?o=yzphoto/pics/3/6/&c=listdelete
    $objectList = OSSHelper::listObjects($object);
    foreach ($objectList as $objectInfo) {
        echo $objectInfo->getKey();
        OSSHelper::deleteObject($objectInfo->getKey());
    }
}