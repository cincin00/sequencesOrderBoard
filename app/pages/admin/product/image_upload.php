<?php

    require_once('../../../../index.php');
    require_once('../../../libraries/product_lib.php');
    require_once('../../../libraries/admin_lib.php');

    $response = '';
    $files = $_FILES['file'];
    $productId = $_GET['product_id'];

    $result = upload_file('product', $files);
    if ($result['result'] === true) {
        $pathTmp = explode('/', $result['path']);
        $index = count($pathTmp);
        $path = '/storage/product/'.$pathTmp[$index-1];
        $files['path'] = $path;
        $files['uuid'] = $result['uuid'];
        $files['product_id'] = $productId;
        uploadProductImage($files);
        $response = '업로드 성공';
    } else {
        $response = '이미지 업로드 실패 사유: '.$result['msg'];
    }

    // dropzone.js callback function Format
    echo json_encode($response);
