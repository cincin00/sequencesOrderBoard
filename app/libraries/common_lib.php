<?php

    /**
     * 디버그용 함수
     */
    function debug($params, int $contuine = 0)
    {
        echo '<pre>';
        print_r($params);
        echo '</pre>';
        if(!$contuine){
            exit;
        }        
    }

    /**
     * debug()함수 별칭
     */
    function dd($params, int $contuine = 0)
    {
        debug($params, $contuine);
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

    /**
     * 쿼리 빌더
     * 
     * @param string $baseQuery 기본 쿼리
     * @param array $params 쿼리 조건
     */
    function queryBuilder(string $table, array $params = ['where'=>'','join'=>[],'groupby'=>'','limit'=>'']){
        // select절
        if(validSingleData($params, 'select')){
            $query = 'SELECT '.$params['select'].' FROM '.$table;
        }else{
            $query = 'SELECT * FROM '.$table;
        }
        
        // join절
        if(validSingleData($params, 'join')){
            foreach($params['join'] as $type => $joinQuery){
                if($type === 'left'){
                    $query .= ' LEFT JOIN '.$joinQuery;
                }elseif($type === 'right'){
                    $query .= ' RIGHT JOIN '.$joinQuery;
                }
            }
        }

        // where절
        if(validSingleData($params, 'where')){
            $query .= ' WHERE '.$params['where'];
        }

        // group by절
        if(validSingleData($params, 'groupby')){
            $query .= ' GROUP BY '.$params['groupby'];
        }

        // having 절
        if(validSingleData($params, 'having')){
            $query .= ' HAVING '.$params['having'];
        }

        // order by절
        if(validSingleData($params, 'orderby')){
            $query .= ' ORDER BY '.$params['orderby'];
        }

        // limit 절
        if(validSingleData($params, 'limit')){
            $query .= ' LIMIT '.$params['limit'];
        }

        if(validSingleData($params, 'debug')){
            if($params['debug'] === true){
                dd($query);
            }            
        }

        return $query;
    }

    /**
     * 전달하는 문자열로 페이지명이 시작되는지 확인
     * 
     * @param string $httpReferer
     * @param string $startPage
     * @return bool
     */
    function checkPage(string $httpReferer, string $startPage)
    {    
        $result = false;
        $parseUrl = parse_url($httpReferer);
        $path = explode('/', $parseUrl['path']);
        $lastIndex = count($path);
        
        $subject = $path[$lastIndex-1];
        $pattern = '/^'.$startPage.'_[a-zA-Z]*.php/smi';
        preg_match($pattern, $subject, $matches);
        if(empty($matches) === false){
            $result = true;
        }

        return $result;
    }