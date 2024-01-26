<?php
require_once('toolkit.php');
trait webhook
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
    public function markdown(){}
}