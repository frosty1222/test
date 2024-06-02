<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Response as Res;
class CustomResponse{
    public static $error_array=array(
        "code"=>"",
        "status"=>"error",
        "message"=>"Error occurs",
    );

    public static $success_array=array(
        "code"=>"",
        "status"=>"success",
        "message"=>"Successfully",
    );

    /** Hàm response
     * @param array $arrayResponse : array response được cấu hình trong model respnse
     * @param $message : nội dung
     * @param bool $die : true : response die | false : response ko die
     * @return array
     */
    public static function response(array $arrayResponse,$message='',$die=false, array $data = null){
        $response=$arrayResponse;
        if($message!="")$response['message']=$message;
        if($data)$response['data']=$data;
        if($die===true){
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        return $response;
    }
    public static function newResponse(){
        return Res::class;
    }
}