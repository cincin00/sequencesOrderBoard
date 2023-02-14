<?php

    require_once('../../../../index.php');
    require_once('../../../libraries/product_lib.php');
    require_once('../../../libraries/admin_lib.php');

    // 초기값 설정
    $response = [];
    $fileData = [];
    // 삭제 요청 파일 정보 가공
    $fileData['product_id'] = (validSingleData($_POST, 'product_id') == true ? $_POST['product_id'] : 0 );
    $fileData['product_path']  = (validSingleData($_POST, 'product_path') == true ? $_POST['product_path'] : '' );
    $fileData['product_image_id']  = (validSingleData($_POST, 'product_image_id') == true ? $_POST['product_image_id'] : 0 );

    // 실물 파일 삭제
    $result = delete_file('product', $fileData);
    // DB 데이터 삭제
    if ($result['result'] === true) {
        deleteProductImage($fileData);
        $response['result'] = true;
        $response['msg'] = '이미지가 삭제되었습니다.';        
    } else {
        $response['result'] = false;
        $response['msg'] = '이미지 업로드 실패 사유: '.$result['msg'];
    }

    // dropzone.js callback function Format
    echo json_encode($response);
