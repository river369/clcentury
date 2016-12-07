<?php

/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/6
 * Time: 21:42
 */
namespace Addons\OverSea\Api;

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

        if(isset($this->data["source"])&&in_array($this->data["source"] ,array("mxyc_ios","mxyc_adr")))
        {
            $compress=true;
        }
        $this->buildResponse($response,$compress);
    }

    public function buildResponse($result,$compress=null)
    {
        $data="";
        if (is_array($result)) {
            if (isset($result['data'])) {
                $results = array('response' => array('code' => $result['code'],'msg' => $result['msg'], 'data' => $result['data']));
            } else {
                $results = array('response' => array('code' => $result['code'],'msg' => $result['msg']));
            }
            $data= self::jsonEncode($results);
        }
        if($compress)
        {
            header("Content-Encoding: gzip");
            $data= gzencode($data, 9, FORCE_GZIP);
        }
        echo $data;
        exit(0);
    }

    public static function jsonEncode($str)
    {
        return json_encode($str, JSON_UNESCAPED_UNICODE);
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
