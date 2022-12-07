<?php

    /**
     * 단일 변수의 정의 및 값 존재 여부 반환 함수
     * 
     * @param string $param
     * @return boolean 
     */
    function validSingleData($param, $key){
        $result = false;
        if (isset($param[$key]) === true && empty($param[$key]) === false) {
            $result = true;
        }

        return $result;
    }

    /**
     * 배열 변수의 정의 및 값 존재 여부 반환 함수
     * 
     * @param array $params
     * @return boolean 
     */    
    function validMultiData($params){
        $result = false;
        // Do Something Here
        return $result;
    }

    /**
     * 메시지를 표시하고 페이지 이동 처리 함수
     * 
     * @param string $msg 표시 메시지
     * @param stirng $location 이동할 위치
     */
    function commonAlert($msg, $location){
        echo '<script>alert(`'.$msg.'`); location.href = "'.$location.'";</script>';
        exit;
    }
?>