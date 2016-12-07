<?php

/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/6
 * Time: 21:42
 */
namespace Addons\OverSea\Api;
use Addons\OverSea\Api\Common;

class Base
{
    public $data;
    
    /**
     * @var array
     */
    private $responseData = null;

    /**
     * @var string
     */
    private $code = '0';

    /**
     * @var string
     */
    private $msg = 'success';

    public function __construct($data)
    {
        $this->data=$data;
    }

    // response the content
    public function response()
    {
        $response = array(
            'code' => $this->getCode(),
            'msg' => $this->getMessage()
        );

        if ($this->getResponseData() != null) {
            $response['data'] = $this->getResponseData();
        }

        $compress=null;
//        if(isset($this->data["source"])&&in_array($this->data["source"] ,array("mxyc_ios","mxyc_adr")))
//        {
//            $compress=true;
//        }
        Common::response($response,$compress);
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMessage($msg)
    {
        $this->msg = $msg;
    }

    public function getMessage()
    {
        return $this->msg;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * 返回响应的数据
     *
     * @return the $responseData
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * 返回响应的数据
     *
     * @param array $responseData
     *
     * @return void
     */
    public function setResponseData($responseData)
    {
        $this->responseData = $responseData;
    }

}
