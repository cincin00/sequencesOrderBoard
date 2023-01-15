<?php
/**
 * 상품 기능 라이브러리
 */
require_once(BASEPATH.'/app/libraries/common_lib.php');

// 상품 테이블
const PRODUCT_TBL = 'product';
// 상품 이미지 테이블
const PRODUCT_IMG_TBL = 'product_img';

/**
 * 상품 데이터 조회
 *
 * @param array $params 조회 조건
 * @param int $fetchType 전체 조회 여부(0:단일조회,1:전체조회)
 * @return array
 */
function getProduct(array $params, int $fetchType = 0)
{
    global $dbh;
    $response = [];

    $table = PRODUCT_TBL;
    $query = queryBuilder($table, $params);
    $result = $dbh->query($query);
    if ($fetchType > 0) {
        $response = $result->fetchAll();
    } else {
        $response = $result->fetch();
    }

    return $response;
}

/**
 * 상품 데이터 갱신
 *
 * @param array $params 갱신 조건
 * @return bool
 */
function updateProduct(array $params)
{
    global $dbh;
    $response = false;

    $table = PRODUCT_TBL;
    $query = '';
    
    // Set 조건
    if(validSingleData($params, 'set')){
        // Table 조건
        if(validSingleData($params, 'table')){
            $query = 'UPDATE '.$params['table'].' SET '.$params['set'];
        } else {
            $query = 'UPDATE '.$table.' SET '.$params['set'];
        }        
    }else{
        $msg = '갱신할 정보가 유효하지 않습니다.';
        $location = ADMIN_DIR.'/product/list.php';

        commonMoveAlert($msg, $location);
    }

    // Where 조건
    if(validSingleData($params, 'where')){
        $query .= ' WHERE '.$params['where'];
    }

    if(validSingleData($params, 'debug')){
        if($params['debug'] === true){
            dd($query);
        }        
    }

    $response = $dbh->exec($query);

    return $response;
}

/**
 * 관리자 상품 목록 조회
 */
function getProductForAdminList()
{
    $productCondtion = [
        'select' => '`product`.*, `product_img`.uuid,`product_img`.origin_name,`product_img`.extension,`product_img`.path',
        'join' => [
            'left' => '`product_img` ON `product_img`.id = `product`.image_id',
        ],
        'where' => '`product`.is_delete = 0',
        //'debug' => true
    ];
    $tmpProduct = getProduct($productCondtion, 1);

    foreach ($tmpProduct as $index => $data) {
        // 번호(id), 상품명(name), 가격(price), 표시여부(is_visible), 등록일(regist_date)
        $product[$index] = $data;
        $product[$index]['id'] = $data['id'];
        $product[$index]['name'] = cutStr($data['name'], 10);
        $product[$index]['price'] = $data['price'];
        $product[$index]['is_visible'] = ($data['is_visible'] === 1 ? '공개' : '비공개');
        $product[$index]['regist_date'] = $data['regist_date'];
    }

    $response = $product;

    return $response;
}

/**
 * 관리자 상품 상세 페이지
 */
function getProductForAdminView(array $params)
{
    // 반환값 초기화
    $response = $msg = $location = '';
    // 상품 조회 데이터 검증
    $valid = validSingleData($params, 'product_id');
    if ($valid === false) {
        $msg = '상품 조회 필수 데이터가 존재하지 않습니다.';
        $location = ADMIN_DIR.'/product/list.php';
        commonMoveAlert($msg, $location);
    }

    // 상품 데이터 조회
    $productCondtion = [
        'select' => '`product`.*, `product_img`.uuid,`product_img`.origin_name,`product_img`.extension,`product_img`.path',
        'join' => [
            'left' => '`product_img` ON `product_img`.id = `product`.image_id',
        ],
        'where' => '`product`.id = '.$params['product_id'],
        //'debug' => true
    ];
    $product = getProduct($productCondtion);
    if (count($product) < 1) {
        $msg = '존재하지 않은 상품입니다.';
        $location = ADMIN_DIR.'/product/list.php';
        commonMoveAlert($msg, $location);
    }

    $response = $product;

    return $response;
}
