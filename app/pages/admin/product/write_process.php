<?php
    require_once('../../../../index.php');
    require_once('../../../libraries/product_lib.php');
    require_once('../../../libraries/admin_lib.php');

    // 입력 데이터 수신
    $params['product_name'] = (isset($_POST['product_name']) === true ? $_POST['product_name'] : '');
    $params['product_price'] = (isset($_POST['product_price']) === true ? $_POST['product_price'] : 0);
    $params['is_visible'] = (isset($_POST['is_visible']) === true ? $_POST['is_visible'] : 0);
    $params['product_status'] = (isset($_POST['product_status']) === true ? $_POST['product_status'] : 0);
    $params['product_desc'] = (isset($_POST['product_desc']) === true ? $_POST['product_desc'] : '');
// var_dump($_REQUEST);var_dump($_POST);var_dump($_FILES);dd('END');
    // 유효성 검증
    validProduct($params);
    // 상품 등록
    $reslut = setProduct($params);

    // 등록 결과 반환
    if($reslut['result']){
        $msg = '상품 등록이 되었습니다.';
        $location = ADMIN_DIR.'/product/modify.php?product_id='.$reslut['product_id'];
    } else {
        $msg = '상품 등록을 실패하였습니다.';
        $location = ADMIN_DIR.'/product/write.php';
    }

    commonMoveAlert($msg, $location);