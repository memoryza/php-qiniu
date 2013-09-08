<?php

namespace Qiniu;

class Mac
{
    public $access_key;
    public $secret_key;

    public function __construct($access_key, $secret_key)
    {
        $this->access_key = $access_key;
        $this->secret_key = $secret_key;
    }

    public function sign($data)
    {
        $sign = hash_hmac('sha1', $data, $this->secret_key, true);
        return $this->access_key . ':' . Util::uriEncode($sign);
    }

    public function signWithData($data)
    {
        $data = Util::uriEncode($data);
        return $this->sign($data) . ':' . $data;
    }

    public function signRequest($url, $body = '')
    {
        $url = parse_url($url);
        $data = '';
        if (isset($url['path'])) {
            $data = $url['path'];
        }
        if (isset($url['query'])) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";

        if ($body) {
            $data .= $body;
        }
        return $this->sign($data);
    }
}