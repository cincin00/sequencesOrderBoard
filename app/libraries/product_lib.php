<?php
/**
 * 상품 기능 라이브러리
 */
require_once(BASEPATH.'/app/libraries/common_lib.php');

// 상품 테이블
const PRODUCT_TBL = 'product';
// 상품 이미지 테이블
const PRODUCT_IMG_TBL = 'product_img';
// 상품 카테고리 테이블
const PRODUCT_CATEGORY_TBL = 'product_category';
// 상품 카테고리 루트 코드명
const ROOT = 'root';

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
    // 반환값 초기화
    $product = [];
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
    $dbh->exec($query);
    // 추가한 이미지 데이터의 순번 반환
    $response = $dbh->lastInsertId();

    return $response;
}

function updateProductImage(array $params)
{
    global $dbh;
    $response = false;

    $table = PRODUCT_IMG_TBL;
    // 수정 처리 시 변경 값은 필수값
    if (validSingleData($params, 'set')) {
        $query = 'UPDATE '.$table.' SET '.$params['set'];
    } else {
        commonAlert('이미지 정보 수정 실패.');
    }

    if (validSingleData($params, 'where')) {
        $query .= ' WHERE '.$params['where'];
    }

    if (validSingleData($params, 'debug')) {
        if ($params['debug'] === true) {
            dd($query);
        }
    }

    $response =$dbh->exec($query);

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
 * 폼에 전달한 이미지 업로드 순번 가공 후 반환
 *
 * @param array $params 이미지 업로드 순번 모음
 * @return array
 */
function getProductImgSeq(array $params)
{
    // 반환값 초기화
    $fileSeq = [];
    // 검증 패턴
    $pattern = '/[\[\]\"]/';
    // 치환 문구
    $replacement = '';
    // 치환 대상
    $subject = $params['files_seq'];
    $regxRes = preg_replace($pattern, $replacement, $subject);
    // 유효한 경우에만 데이터 할당
    if ($regxRes) {
        $fileSeq = explode(',', $regxRes);
    }


    return $fileSeq;
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
    $result = true;
    $msg = $location = '';
    if (validSingleData($params, 'product_name') === false) {
        $result = false;
        $msg = '상품명은 필수 입니다.';
        $location = ADMIN_DIR.'/product/write.php';
    }
    if (validSingleData($params, 'product_price') === false) {
        $result = false;
        $msg = '상품 가격은 필수 입니다.';
        $location = ADMIN_DIR.'/product/write.php';
    }

    return [$result, $msg, $location];
}

/**
 * 상품 카테고리 조회
 *
 * @param array $params 조회 조건
 * @param int $fetchType 전체 조회 여부(0:단일조회,1:전체조회)
 * @return array
 */
function getCategory(array $params, int $fetchType = 0)
{
    global $dbh;
    $response = [];

    $table = PRODUCT_CATEGORY_TBL;
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
 * 관리자 카테고리 목록
 *
 * @param
 * @return json
 */
function getCategoryForAdminCategoryList()
{
    $response = null;
    $categoryCondtion = [
        'where' => '1 = 1',
        'orderby' => 'category_code ASC',
    ];
    $categoryTemp = getCategory($categoryCondtion, 1);

    foreach ($categoryTemp as $index => $data) {
        // 최상위 노드
        if ($data['depth'] === 1) {
            $category[$index]['id'] = $data['category_code'];
            $category[$index]['parent'] = '#';
            $category[$index]['text'] = $data['name'];
            $category[$index]['state']['opened'] = true;
            //$category[$index]['state']['selected'] = true;
            $category[$index]['category_code'] = $data['category_code'];
        } else {
            $category[$index]['id'] = $data['category_code'];
            $category[$index]['parent'] = setTreeNodeParent($data);
            $category[$index]['text'] = $data['name'];
            $category[$index]['state']['opened'] = true;
            $category[$index]['category_code'] = $data['category_code'];
        }
    }

    $response = json_encode($category);

    return $response;
}

/**
 * 노드의 부모 카테고리를 찾는 함수
 */
function setTreeNodeParent(array $data)
{
    // 반환값 초기화
    $response = '';

    $slashPos = ($data['depth'] - 2);
    $parentCode = substr($data['category_code'], 0, $slashPos);
    $response =  (empty($parentCode) === false ? $parentCode : 'root');

    return $response;
}

/**
 * 상품 카테고리 저장
 *
 * @param array $params
 * @return bool
 */
function setCategory(array $params)
{
    global $dbh;
    $response = false;
    $table = PRODUCT_CATEGORY_TBL;
    $query = 'INSERT INTO '.$table.'(`name`, `depth`, `depth_order` , `category_code`) VALUES("'.$params['name'].'", "'.$params['depth'].'", "'.$params['depth_order'].'", "'.$params['category_code'].'")';

    $response = $dbh->exec($query);

    return $response;
}

/**
 * 카테고리 코드 생성
 *
 * @param int $depth 생성된 노드의 깊이
 * @param int $order 생성된 노드의 순서
 * @param string $parentCode 생성된 노드의 부모 카테고리 코드
 * @return string
 */
function setCategoryCode(int $depth, int $order, string $parentCode)
{
    // 카테고리 코드 초기화
    $categoryCode = '';
    // 매핑 데이터
    $baseCode = [
        '0' => 'a',
        '1' => 'b',
        '2' => 'c',
        '3' => 'd',
        '4' => 'e',
        '5' => 'f',
        '6' => 'g',
        '7' => 'h',
        '8' => 'i',
        '9' => 'j',
        '10' => 'k',
        '11' => 'l',
        '12' => 'm',
        '13' => 'n',
        '14' => 'o',
        '15' => 'p',
        '16' => 'q',
        '17' => 'r',
        '18' => 's',
        '19' => 't',
        '20' => 'u',
        '21' => 'v',
        '22' => 'w',
        '23' => 'x',
        '24' => 'y',
        '25' => 'z',
    ];
    if ($order > 25) {
        echo('1차 카테고리는 최대 25개까지만 추가할수 있습니다.');
    }
    if ($depth === 2 && $parentCode === ROOT) {
        // 첫번째 카테고리
        $categoryCode = $baseCode[$order];
    } else {
        $categoryCode = $parentCode.$baseCode[$order];
    }

    return $categoryCode;
}

/**
 * 관리자 카테고리 삭제 목록
 *
 * @param string $categoryCode
 * @param int $depth
 * @return array
 */
function getCategoryForAdminCategoryDelete(string $categoryCode, int $depth)
{
    $response = [];
    $categoryCondtion = [
        'where' => 'depth >= '.$depth.' AND category_code LIKE "'.$categoryCode.'%"',
    ];
    $categoryTemp = getCategory($categoryCondtion, 1);

    foreach ($categoryTemp as $data) {
        $response[] = $data['category_code'];
    }

    return $response;
}

/**
 * 카테고리 삭제
 *
 * @param string $parmas
 * @return bool
 */
function deleteCategory(string $categoryCode)
{
    global $dbh;
    $response = false;
    $table = PRODUCT_CATEGORY_TBL;
    $query = 'DELETE FROM '.$table.' WHERE category_code = "'.$categoryCode.'"';

    $response = $dbh->exec($query);

    return $response;
}

/**
 * 카테고리명 수정
 *
 * @param string $categoryName
 * @param string $categoryCode
 */
function renameCategory(string $categoryName, string $categoryCode)
{
    // 반환값 초기화
    $response = false;
    // 업데이트문 조건식
    $updateCondtion = [
        'set' => 'name = "'.$categoryName.'"',
        'where' => 'category_code = "'.$categoryCode.'"',
        //'debug' => true
    ];
    $response = updateCategory($updateCondtion);

    return $response;
}

function updateCategory(array $params)
{
    global $dbh;
    $response = false;
    $table = PRODUCT_CATEGORY_TBL;


    // 수정 처리 시 변경 값은 필수값
    if (validSingleData($params, 'set')) {
        $query = 'UPDATE '.$table.' SET '.$params['set'];
    } else {
        commonAlert('카테고리 정보 수정 실패.');
    }

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
