<?php
function request($method, $url, $header, $body = null)
    {
        $ch = curl_init($url);
        if ($method == "GET") {
            //不执行
        } elseif ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
        } elseif ($method == "PUT") {
            curl_setopt($ch, CURLOPT_PUT, 1);
        } else {
            throw new Exception("Error Processing Request:Bad Method", 1);
        }
        if ($header == null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('charset=utf-8'));
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if ($method == "GET") {
            if ($body != null) {
                throw new Exception("Error Processing Request:Body is not needed", 1);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
        }
        $r = curl_exec($ch);
        return $r;
    }
trait Webhook
{
    public function text($text, $webhook)
    {
        $body = [
            'msgtype' => 'text',
            'text' => [
                'content' => $text
            ]
        ];
        try {
            $result = request('GET',$webhook,null,$body);
        } catch (Exception $e) {
            $result = '发送请求时出错';
        }
        return $result;
    }
}

class Bot {
    private $ar;
    private $bb;
    public $resu;
    public $zl;
    public $cs;
    public $session;
    use Webhook;
    public function __construct() {
        $body = @file_get_contents('php://input');
        $json = json_decode($body,true);
        $message = $json['text']['content'];
        $session = $json = ['sessionWebhook'];
        $this->ar = explode(' ', $message);
        $this->zl = $this->ar[0];
        $this->cs = str_replace($this->zl,'',$message);
        if ($this->zl == '/人工智能') {
            $ai = $this->ai($this->cs);
            $this->text($ai,$session);
        } else {
            $this->text('使用方法：/人工智能 内容',$session);
        }
        
    }
    public function ai($message) {
        $messa = [
            'role' => 'user',
            'content' => $message
        ];
        $bb = [
            'model' => 'Creative-g4t',
            'message' => array($messa)
        ];
        $r = request('POST','https://harry-zklcdc-go-proxy-bingai.hf.space/v1/chat/completions',null,$bb);
        return $r;
    }
}
$bot = new Bot();
