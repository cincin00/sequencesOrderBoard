<?php

    /**
     * 디버그용 함수
     */
    function debug($params)
    {
        echo '<pre>';
        print_r($params);
        echo '</pre>';
        exit;
    }

    /**
     * debug()함수 별칭
     */
    function dd($param)
    {
        debug($param);
    }
    /**
     * 단일 변수의 정의 및 값 존재 여부 반환 함수
     *
     * @param array $param
     * @param string $key
     * @return boolean
     */
    function validSingleData($param, $key)
    {
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
    function validMultiData($params)
    {
        $result = false;
        // Do Something Here
        return $result;
    }

    /**
     * 메시지를 표시하고 페이지 이동 처리 함수
     *
     * @param string $msg 표시 메시지
     * @param stirng $location 이동할 위치
     * @return void
     */
    function commonAlert($msg)
    {
        echo '<script>alert(`'.$msg.'`);</script>';
        exit;
    }

    /**
     * 메시지를 표시하고 페이지 이동 처리 함수
     *
     * @param string $msg 표시 메시지
     * @param stirng $location 이동할 위치
     * @return void
     */
    function commonMoveAlert($msg, $location)
    {
        echo '<script>alert(`'.$msg.'`); location.href = "'.$location.'";</script>';
        exit;
    }
