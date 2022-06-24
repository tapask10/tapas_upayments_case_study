<?php

namespace App\Repository;

class Response
{

    public static function Success($data=[], $message = null){
        $response = [];
        $response['status'] = "success";
        $response['data'] = $data;
        $response['message'] = $message;
        return json_encode($response);
    }

    public static function Error($data=[], $message = null){
        $response = [];
        $response['status'] = "error";
        $response['data'] = $data;
        $response['message'] = $message;
        return json_encode($response);
    }

    public static function ValidationFormater($multiDimensionalArray){
        
        $singleArray = array();

        foreach ($multiDimensionalArray as $arr ){

            foreach($arr as $value){
                $singleArray[] = $value;
            }

        }
       return $singleArray;

    }

    public static function ValidationError($data=[], $message = null){
        $response = [];
        $response['status'] = "error";
        $response['data'] = $data;
        $response['message'] = $message;
        return $response;
    }

}