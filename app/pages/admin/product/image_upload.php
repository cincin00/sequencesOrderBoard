<?php

    require_once('../../../../index.php');
    require_once('../../../libraries/product_lib.php');
    require_once('../../../libraries/admin_lib.php');

    /**
     * 이미지 등록, 수정을 한번에 관리하는 페이지
     * [필요 정보]
     * - 파일 정보: 파일이름(name), 파일 사이즈(size), 파일 경로(path), 임시 경로(temp_path)
     * - 상품 순번(수정)
     */
    $response = [];
    $files = (isset($_FILES['file']) === true ? $_FILES['file'] : null);
    $productId = (isset($_GET['product_id']) === true ? $_GET['product_id'] : 0);
    // 데이터 정제
    foreach ($files as $index => $data) {
        foreach ($data as $i => $d) {
            $refineFiles[$i][$index] = $d;
        }
    }

    if (count($refineFiles) <= 5) {
        foreach ($refineFiles as $file) {
            // 실물 파일 복사
            $result = upload_file('product', $file);
            if ($result['result'] === true) {
                $pathTmp = explode('/', $result['path']);
                $index = count($pathTmp);
                $path = '/storage/product/'.$pathTmp[$index-1];
                $files['path'] = $path;
                $files['uuid'] = $result['uuid'];
                $files['product_id'] = $productId;
                // 파일 정보 DB 추가
                $uploadRes = uploadProductImage($files);
                if ($uploadRes !== false) {
                    $response[] = $uploadRes;
                }
            } else {
                // 업로드 실패
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-type: text/plain');
                $response = $result['msg'];
            }
        }
    } else {
        $response = '최대 업로드 수량을 초과하였습니다.';
    }

    echo json_encode($response);
