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
    $params['files_seq'] = (isset($_POST['files_seq']) === true ? $_POST['files_seq'] : []);

    // 유효성 검증
    validProduct($params);
    // 상품 등록
    $reslut = setProduct($params);

    if ($reslut['result']) {
        // 등록 이미지 있는 경우 맵핑
        if (empty($params['files_seq']) === false) {
            $pattern = '/[\[\]\"]/';
            $replacement = '';
            $subject = $params['files_seq'];
            $regxRes = preg_replace($pattern, $replacement, $subject);
            $fileSeq = explode(',', $regxRes);
            foreach ($fileSeq as $seq) {
                $updateFileImgCondtion = [
                    'set' => 'product_id = ' . $reslut['product_id'],
                    'where' => 'id = ' . $seq,
                    'debug' => false
                ];
                $updateRes = updateProductImage($updateFileImgCondtion);
            }
        }
        // 등록 결과 반환
        $msg = '상품 등록이 되었습니다.';
        $location = ADMIN_DIR.'/product/modify.php?product_id='.$reslut['product_id'];
    } else {
        $msg = '상품 등록을 실패하였습니다.';
        $location = ADMIN_DIR.'/product/write.php';
    }

    /**
     * 잉여 데이터 삭제
     * - 이미지 데이터 DB에서 삭제
     * - 실물 정보 서버에서 삭제
     */

    // 상품 등록 결과 반환
    commonMoveAlert($msg, $location);
