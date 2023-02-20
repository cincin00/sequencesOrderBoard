<?php

    /**
     * 디버그용 함수
     */
    function debug($params, int $contuine = 0)
    {
        echo '<pre>';
        print_r($params);
        echo '</pre>';
        if (!$contuine) {
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
    function queryBuilder(string $table, array $params = ['where'=>'','join'=>[],'groupby'=>'','limit'=>''])
    {
        // select절
        if (validSingleData($params, 'select')) {
            $query = 'SELECT '.$params['select'].' FROM '.$table;
        } else {
            $query = 'SELECT * FROM '.$table;
        }

        // join절
        if (validSingleData($params, 'join')) {
            foreach ($params['join'] as $type => $joinQuery) {
                if ($type === 'left') {
                    $query .= ' LEFT JOIN '.$joinQuery;
                } elseif ($type === 'right') {
                    $query .= ' RIGHT JOIN '.$joinQuery;
                }
            }
        }

        // where절
        if (validSingleData($params, 'where')) {
            $query .= ' WHERE '.$params['where'];
        }

        // group by절
        if (validSingleData($params, 'groupby')) {
            $query .= ' GROUP BY '.$params['groupby'];
        }

        // having 절
        if (validSingleData($params, 'having')) {
            $query .= ' HAVING '.$params['having'];
        }

        // order by절
        if (validSingleData($params, 'orderby')) {
            $query .= ' ORDER BY '.$params['orderby'];
        }

        // limit 절
        if (validSingleData($params, 'limit')) {
            $query .= ' LIMIT '.$params['limit'];
        }

        if (validSingleData($params, 'debug')) {
            if ($params['debug'] === true) {
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
        if (empty($matches) === false) {
            $result = true;
        }

        return $result;
    }

    /**
     * 문자열 자르기
     *
     * @description
     * - 주어진 문자열($str)을 전달 받은 길이 값($length)으로 잘라 반환
     * @param string $str
     * @param string $length
     */
    function cutStr(string $str, int $length)
    {
        // 반환 값 초기화
        $result = $msg = '';

        // 문자열 유효성 검증
        if (empty($str)) {
            $msg = '기준 문자열은 필수입니다.';
        } elseif (empty($length)) {
            $msg = '기준 길이값은 필수입니다.';
        }

        if ($msg) {
            commonAlert($msg);
        }

        $strLen = mb_strlen($str, 'UTF-8');
        if ($strLen > $length) {
            $result = mb_substr($str, 0, $length, 'UTF-8');
        } else {
            $result = $str;
        }

        return $result;
    }

    /**
     * 파일 업로드 함수
     *
     * @param string $folder 이미지 업로드 폴더명
     * @param array $params 이미지 정보
     */
    function upload_file(string $folder, array $params)
    {
        // 반환값 초기화
        $response = [
            'result' => false,
            'uuid' => '',
            'path' => '',
            'msg' => '',
        ];

        // 업로드 경로
        $folderPath = PATH_STORAGE.'/'.$folder.'/';
        // 파일명 - 파일 정보
        $fileName = $params['name'];
        // 파일 확장자 - 파일 정보
        $fileExt = $params['type'];
        // 파일 크기 - 파일 정보
        $fileSize = $params['size'];
        // 임시 업로드 경로
        $tmpPath = $params['tmp_name'];
        // 파일 사이즈 검증 - 최대 5MB(1024*5)
        $maxSize = 5 * 1024 * 1024;
        if ($maxSize < $fileSize) {
            $response['msg'] = '최대 사이즈 초과하는 파일입니다.';
            return $response;
        }
        // 파일 확장자 검증 - jpg,jpeg,png,gif
        $extension = ['jpg','jpeg', 'png', 'gif'];
        $uploadExt = explode('/', $fileExt)[1];
        if (in_array($uploadExt, $extension) === false) {
            $response['msg'] = '허용되지 않는 파일 확장자입니다.';
            return $response;
        }
        // 파일 업로드 폴더 검증(권한/존재여부)
        // 파일 업로드(복사) - 성공 / 실패
        $uuid = uniqid();
        $response['result'] = copy($tmpPath, $folderPath.$uuid.'_'.$fileName);
        $response['path'] = $folderPath.$uuid.'_'.$fileName;
        $response['uuid'] = $uuid;

        return $response;
    }

    /**
     * 파일 삭제 함수
     *
     * @param string $folder 이미지 삭제 폴더명
     * @param array $params 이미지 정보
     */
    function delete_file(string $folder, array $params)
    {
        // 반환값 초기화
        $response = [
            'result' => false,
            'path' => '',
            'msg' => '',
        ];

        try {
            // 파일 존재 검증
            $fileName = '/opt/homebrew/var/www/board01/public_html'.$params['product_path'];
            // if(file_exists($fileName) === false){
            //     throw new Exception('존재하지 않는 파일이거나 경로가 올바르지 않습니다.',);
            // }
            // 파일 삭제 - 성공 / 실패
            $response['result'] = unlink($fileName);
            if ($response['result'] === false) {
                throw new Exception('존재하지 않는 파일이거나 경로가 올바르지 않습니다.', );
            }
        } catch(Exception $e) {
            $response['msg'] = $e->getMessage();
        }

        return $response;
    }
