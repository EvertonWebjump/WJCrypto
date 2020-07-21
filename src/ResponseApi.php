<?php


namespace Framework;

use Symfony\Component\HttpFoundation\Response;

class ResponseApi
{
    static function jsonResponse($error = true, $message = '', $data = array(), $status = 200 )
    {
        $dataResponse = new \stdClass;
        $dataResponse->error = $error;
        $dataResponse->message = $message;
        $dataResponse->data = $data;

        $response = new Response(
            json_encode($dataResponse),
            $status,
            ['content-type' => 'text/html']
        );

        return $response->send();
    }

}