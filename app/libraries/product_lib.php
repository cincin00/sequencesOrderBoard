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
    if (validSingleData($params, 'set')) {
        // Table 조건
        if (validSingleData($params, 'table')) {
            $query = 'UPDATE '.$params['table'].' SET '.$params['set'];
        } else {
            $query = 'UPDATE '.$table.' SET '.$params['set'];
        }
    } else {
        $msg = '갱신할 정보가 유효하지 않습니다.';
        $location = ADMIN_DIR.'/product/list.php';

        commonMoveAlert($msg, $location);
    }

    // Where 조건
    if (validSingleData($params, 'where')) {
        $query .= ' WHERE '.$params['where'];
    }

    if (validSingleData($params, 'debug')) {
        if ($params['debug'] === true) {
            dd($query);
        }
    }

    $response = $dbh->exec($query);

    return $response;
}

/**
 * 상품 목록 페이지 데이터 조회
 * @return array
 */
function getProductForSkinList()
{
    // 목록 조회 조건
    $productCondtion = [
        'select' => '`product`.*, MAX(`product_img`.id) AS img_id, MIN(`product_img`.path) AS img_path',
        'join' => [
            'left' => '`product_img` ON `product`.id = `product_img`.product_id'
        ],
        'where' => '`product`.is_delete = 0 AND is_visible = 1',
        'groupby' => '`product`.id',
    ];
    $tmpProduct = getProduct($productCondtion, 1);
    $nameLimitLength = 15;
    $descLimitLength = 30;

    foreach ($tmpProduct as $index => $data) {
        // 번호(id), 상품명(name), 가격(price), 표시여부(is_visible), 등록일(regist_date)
        $product[$index] = $data;
        $product[$index]['id'] = $data['id'];
        $product[$index]['name'] = (mb_strlen($data['name']) > $nameLimitLength ? cutStr($data['name'], $nameLimitLength).'....' : $data['name']);
        $product[$index]['description'] = (mb_strlen($data['description']) > $descLimitLength ? cutStr(htmlspecialchars_decode($data['description']), $descLimitLength).'....' : htmlspecialchars_decode($data['description']));
        $product[$index]['price'] = $data['price'];
        $product[$index]['is_visible'] = ($data['is_visible'] === 1 ? '공개' : '비공개');
        $product[$index]['regist_date'] = $data['regist_date'];
        $product[$index]['img_path'] = (empty($data['img_path']) === true ? PATH_COMMON_RESOURCE.'/no_image.jpg' : DOMAIN.$data['img_path']);
    }

    $response = $product;

    return $response;
}

/**
 * 상품 상세 페이지 데이터 조회
 *
 * @param array $params
 * @return array
 */
function getProductForSkinView(array $params)
{
    // 반환값 초기화
    $response = [];
    $msg = $location = '';
    // 상품 조회 데이터 검증
    $valid = validSingleData($params, 'product_id');
    if ($valid === false) {
        $msg = '상품 조회 필수 데이터가 존재하지 않습니다.';
        $location = PAGES_DIR.'/product/list.php';
        commonMoveAlert($msg, $location);
    }

    // 상품 데이터 조회
    $productCondtion = [
        'where' => '`product`.id = '.$params['product_id'] . ' AND `product`.is_delete = 0 AND `product`.is_visible = 1',
        //'debug' => true
    ];
    $product = getProduct($productCondtion);
    if (empty($product) || count($product) < 1) {
        $msg = '존재하지 않은 상품입니다.';
        $location = PAGES_DIR.'/product/list.php';
        commonMoveAlert($msg, $location);
    }
    // 상품 이미지 조회
    $productImgCondtion = [
        'where' => 'product_id = '.$params['product_id'],
        //'debug' => true
    ];
    $productImg = getProductImage($productImgCondtion, 1);
    $response = $product;
    $response['description'] = htmlspecialchars_decode($product['description']);
    $response['product_img'] = $productImg;

    return $response;
}

/**
 * 관리자 상품 목록 조회
 *
 * @return array
 */
function getProductForAdminList()
{
    // 목록 조회 조건
    $productCondtion = [
        'select' => '`product`.*, MAX(`product_img`.id) AS img_id, MIN(`product_img`.path) AS img_path',
        'join' => [
            'left' => '`product_img` ON `product`.id = `product_img`.product_id'
        ],
        'where' => '`product`.is_delete = 0',
        'groupby' => '`product`.id',
    ];
    $tmpProduct = getProduct($productCondtion, 1);

    $nameLimitLength = 15;

    // @todo: front 에서 처리하자!
    foreach ($tmpProduct as $index => $data) {
        // 번호(id), 상품명(name), 가격(price), 표시여부(is_visible), 등록일(regist_date)
        $product[$index] = $data;
        $product[$index]['id'] = $data['id'];
        $product[$index]['name'] = (mb_strlen($data['name']) > $nameLimitLength ? cutStr($data['name'], $nameLimitLength).'....' : $data['name']);
        $product[$index]['price'] = $data['price'];
        $product[$index]['is_visible'] = ($data['is_visible'] === 1 ? '공개' : '비공개');
        $product[$index]['regist_date'] = $data['regist_date'];
        $product[$index]['img_path'] = (empty($data['img_path']) === true ? PATH_COMMON_RESOURCE.'/no_image.jpg' : DOMAIN.$data['img_path']);
    }

    $response = $product;

    return $response;
}

/**
 * 관리자 상품 상세 페이지
 *
 * @param array 데이터 조회 조건
 * @return array
 */
function getProductForAdminView(array $params)
{
    // 반환값 초기화
    $response = [];
    $msg = $location = '';
    // 상품 조회 데이터 검증
    $valid = validSingleData($params, 'product_id');
    if ($valid === false) {
        $msg = '상품 조회 필수 데이터가 존재하지 않습니다.';
        $location = ADMIN_DIR.'/product/list.php';
        commonMoveAlert($msg, $location);
    }

    // 상품 데이터 조회
    $productCondtion = [
        'where' => '`product`.id = '.$params['product_id'],
    ];
    $product = getProduct($productCondtion);
    if (count($product) < 1) {
        $msg = '존재하지 않은 상품입니다.';
        $location = ADMIN_DIR.'/product/list.php';
        commonMoveAlert($msg, $location);
    }
    // 상품 이미지 조회
    $productImgCondtion = [
        'where' => 'product_id = '.$params['product_id'],
        //'debug' => true
    ];
    $productImg = getProductImage($productImgCondtion, 1);
    $response = $product;
    $response['product_img'] = $productImg;

    return $response;
}

/**
 * 상품 이미지 업로드
 *
 * @param array $params 파일 정보
 * @return bool
 */
function uploadProductImage(array $params)
{
    global $dbh;
    $response = false;

    $table = PRODUCT_IMG_TBL;
    $query = 'INSERT INTO '.$table.'(`product_id`, `uuid`, `origin_name`, `extension` , `size`, `path`, `upload_date`) VALUES("'.$params['product_id'].'", "'.uniqid().'", "'.$params['name'][0].'", "'.$params['type'][0].'", "'.$params['size'][0].'", "'.$params['path'].'", "'.date('Y-m-d H:i:s').'")';

    $response = $dbh->exec($query);

    return $response;
}

/**
 * 상품 이미지 정보 조회
 *
 * @param array $params 상품 이미지 검색 데이터
 * @param int $fetchType 전체 조회 여부(0:단일조회,1:전체조회)
 * @return array
 */
function getProductImage(array $params, int $fetchType = 0)
{
    global $dbh;
    $response = [];

    $table = PRODUCT_IMG_TBL;
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
 * 상품 이미지 삭제
 *
 * @param array 상품 이미지 정보
 * @return bool
 */
function deleteProductImage(array $params)
{
    global $dbh;
    $response = false;

    $table = PRODUCT_IMG_TBL;
    $query = 'DELETE FROM '.$table.' WHERE product_id = ' . $params['product_id'] . ' AND id = ' . $params['product_image_id'];
    $result = $dbh->query($query);

    $response = $result;

    return $response;
}

/**
 * 상품 데이터 저장
 *
 * @param array
 * @return bool
 */
function setProduct(array $params)
{
    global $dbh;
    $response = [];

    $table = PRODUCT_TBL;
    $query = 'INSERT INTO '.$table.' (`name`, `description`, `price`, `status`, `is_visible`, `regist_date`) VALUES ("'.$params['product_name'].'", "'.$params['product_desc'].'", "'.$params['product_price'].'", "'.$params['product_status'].'", "'.$params['is_visible'].'", "'.date('Y-m-d H:i:s').'")';
    $response['result'] = $dbh->exec($query);
    $response['product_id'] = $dbh->lastInsertId();

    return $response;
}

/**
 * 상품 등록 유효성 검증
 */
function validProduct(array $params)
{
    $msg = $location = '';
    if (validSingleData($params, 'product_name') === false) {
        $msg = '상품명은 필수 입니다.';
        $location = ADMIN_DIR.'/product/write.php';
    }

    if ($msg && $location) {
        commonMoveAlert($msg, $location);
    }
}
