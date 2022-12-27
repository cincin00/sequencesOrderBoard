<?php
/**
 * 게시판 기능 라이브러리
 */
require_once(BASEPATH.'/app/libraries/common_lib.php');

/**
 * 게시판 설정 조회
 *
 * @param array $params 게시판 조회 조건
 * @param int $fetchType 데이터 반환 수(0: 단일, 1: 전체)
 * @return array
 */
function getBoard(array $params, int $fetchType = 0)
{
    global $dbh;
    $response = [];

    $table = "board";
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
 * 게시글 목록에서 사용하는 페이징 함수
 *
 * @param array $params 페이징 데이터
 * @param int $boardId 게시판 아이디
 * @return array
 */
function getPagingData(array $params, int $boardId)
{
    // 현재 페이지 번호(기본: 1페이지)
    $currentPage = (isset($params['page']) === true ? $params['page'] : 1);
    // 게시글 전체 수량(삭제 처리되지 않은 게시글만 조회)
    if (validSingleData($params, 'row_params')) {
        $rowParams = $params['row_params'];
    } else {
        $rowParams = [
            'select' => 'COUNT(*) as `total_cnt`',
            'where' => 'board_id = '.$boardId.' AND is_delete = 0'
        ];
    }

    $totalRow = getPost($rowParams)['total_cnt'];
    // 1개 페이지에 표시할 게시글 수
    $length = 10;
    // 전체 페이지 수 - 0인 경우 1 페이지로 고정
    $totalPage = ceil($totalRow/$length) > 0 ? ceil($totalRow/$length) : 1;
    // 처음 페이지
    $firstPage =  1 ;
    // 이전 페이지
    $prePage = ($currentPage - 1) > 0 ? $currentPage - 1 : 1;
    // 다음 페이지
    $nextPage = ($currentPage + 1) <= $totalPage ? $currentPage + 1 : $totalPage ;
    // 마지막 페이지
    $lastPage = $totalPage;
    //현재 시작 게시글 번호
    $startRow = ($currentPage - 1) * $length;
    // 블록당 페이지 수
    //$block = 5;
    // 현재 블록 번호 ( 현재 페이지 / 블록당 페이지 수 )
    //$currentBlock = ceil($currentPage / $block);
    // 전체 블록 번호 ( 전체 페이지 / 블록당 페이지 수 )
    //$totalBlock = ceil($totalPage / $block);

    return [$firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow];
}

/**
 * 게시글 데이터 조회
 *
 * @param 검색 조건
 * @param int $fetchType 데이터 반환 수(0: 단일, 1: 전체)
 * @return array
 */
function getPost(array $params, int $fetchType = 0)
{
    global $dbh;
    $response = [];
    $table = "post";
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
 * 게시판 목록용 데이터 조회 함수
 * 
 * @param array $params
 * @return array
 */
function getPostForList($params)
{
    // 게시판 설정 로드 - 계층형 게시판(1) 고정
    $boardData = getBoard(['where'=>1]);
    // 페이징 처리
    list($firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow) = getPagingData($params, 1);
    // 게시글 정보 조회 - 계층형 게시글 정렬 및 페이징 처리
    $params = [
        'select' => '`post`.*, `bc`.title as `category_title`',
        'join' => [
            'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
        ],
        'where' => '`post`.board_id = 1 AND `post`.is_delete = 0',
        'orderby' => '`post`.group_id DESC, `post`.group_order ASC, `post`.group_depth DESC',
        'limit' => $startRow.", ".$length,
      ];
    $postData = getPost($params, 1);

    return [$boardData, $postData, $firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow];
}

/**
 * 마이페이지 나의 게시글 목록용 데이터 조회 함수
 * 
 * @param array $params
 * @return array
 */
function getPostForMypostList($params)
{
    $boardId = 1;

    // 페이징 처리
    $params['row_params'] = [
        'select' => 'COUNT(*) as `total_cnt`',
        'where' => 'board_id = '.$boardId.' AND is_delete = 0 AND member_id = "'.$_SESSION['id'].'" '
    ];

    list($firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow) = getPagingData($params, 1);
    // 게시글 정보 조회 - 계층형 게시글 정렬 및 페이징 처리
    $params = [
      'select' => '`post`.*, `bc`.title as `category_title`',
      'join' => [
          'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
      ],
      'where' => '`post`.board_id = 1 AND `post`.is_delete = 0 AND member_id = "'.$_SESSION['id'].'"' ,
      'orderby' => '`post`.group_id DESC, `post`.group_order ASC, `post`.group_depth DESC',
      'limit' => $startRow.", ".$length,
    ];
    $postData = getPost($params, 1);

    return [$boardId, $postData,  $firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow];
}

/**
 * 게시글 수정
 * @todo board_category Error Fix
 * @param array $params
 * @return object
 */
function modifyPost(array $params)
{
    global $dbh;
    $postId = $params['post_id'];
    $title = $params['title'];
    $contents = htmlentities($params['contents']);
    $boardCategory = $params['board_category'] ?? '';
    $modifyDate = date('Y-m-d H:i:s');

    // 데이터 저장
    $set = "title = '$title', contents = '$contents', modify_date = '$modifyDate'";
    if ($boardCategory) {
        $set .= ", board_category = '$boardCategory'";
    }
    $query = "UPDATE post SET $set WHERE id = $postId";
    $response = $dbh->exec($query);

    return $response;
}

/**
 * 게시글 답글 여부 확인 함수
 *
 * @param int $id 삭제할 게시글 아이디
 * @return bool
 */
function havePostReplay(int $id = 0)
{
    global $dbh;
    $query = 'SELECT COUNT(*) as `reply_num` FROM post WHERE is_delete = 0 AND group_id = '.$id;
    $sth = $dbh->query($query);
    $queryResult = $sth->fetch();

    return $queryResult['reply_num'] > 1 ? true : false;
}

/**
 * 게시글 카테고리 조회
 *
 * @param array $params 조회 조건
 * @param int $fetchType 데이터 조회 뱡식(0:단일,1:전체)
 * @return object
 */
function getCategoryData(array $params = [], int $fetchType = 0)
{
    global $dbh;
    $response = [];
    // 카테고리 조회 기본 쿼리
    $table = "board_category";
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
 * 게시글 작성 페이지용 데이터 조회 함수
 * @param array $params 
 * @return array
 */
function boardWriteViewData(array $params)
{
    
    $boardId = isset($params['board_id']) === true ? $params['board_id'] : 0;

    // 게시판 설정 로드 - 게층형 게시판 고정
    $boardData = getBoard(['where'=>$boardId]);
    if (empty($boardData) === true) {
        commonMoveAlert('존재하지 않는 게시판입니다.',BOARD_DIR.'/list.php');
    }

    // 게시판 카테고리 로드
    $categoryResult = getCategoryData(['where'=>'board_id = '.$boardId, 'orderby'=>'sort_order'], 1);

    foreach ($categoryResult as $categoryData) {
        $category[$categoryData['sort_order']] = [
            'category_id' => $categoryData['id'],
            'title' => $categoryData['title']
        ];
    }

    // 답글 기능 로드
    $replyPostData = [];
    if (isset($params['reply']) === true && empty($params['reply']) === false) {
        $replyId = $params['reply'];
        if ($replyId > 0) {
            $condition = [
                'select' => '`post`.*, `bc`.title as `category_title`',
                'join' => [
                    'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
                ],
                'where' => '`post`.id = "'.$replyId.'" AND `post`.board_id = "'.$boardId.'"',
            ];
            $replyPostData = getPost($condition);
        } else {
            commonMoveAlert('존재하지 않은 게시글입니다.', BOARD_DIR.'/list.php');
        }
    }

    // 로그인 검증
    $isLogin = (isset($_SESSION['id']) === true ? true : false);

    return [$boardId, $boardData, $category, $replyPostData, $isLogin];
}

/**
 * 게시판 상세 페이지용 데이터 조회 함수
 */
function boardViewData(array $params)
{
    $baordId = isset($params['board_id']) === true ? $params['board_id'] : 0;
    $postId = isset($params['id']) === true ? $params['id'] : 0;

    // 게시판 설정 로드
    $boardData = getBoard(['where'=>$baordId]);
    if (empty($boardData) === true) {
        commonMoveAlert('존재하지 않는 게시판입니다.',BOARD_DIR.'/list.php');
    }

    // 게시글 정보 조회
    $condition = [
        'select' => '`post`.*, `bc`.title as `category_title`',
        'join' => [
            'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
        ],
        'where' => '`post`.id = "'.$postId.'" AND `post`.board_id = "'.$baordId.'"',
    ];
    $postData = getPost($condition);
    if (empty($postData) === true) {
        commonMoveAlert('존재하지 않는 게시글입니다.',BOARD_DIR.'/list.php');
    }

    // 조회수 증가 처리
    postHitIncrease(['post_id' => $postId]);

    // 비회원, 회원 게시글 분기 처리
    $isMember = $isOwn = $isMemberOwn = false;
    if ($postData['member_id']) {
        $isMemberOwn = true;
        if (isset($_SESSION['id'])) {
            $isMember = true;
            if ($postData['member_id'] === $_SESSION['id']) {
                $isOwn = true;
            }
        }
    }

    return [$boardData, $postData, $isMember, $isOwn, $isMemberOwn];
}

function mypostViewData(array $params)
{
    $baordId = isset($params['board_id']) === true ? $params['board_id'] : 0;
    $postId = isset($params['id']) === true ? $params['id'] : 0;

    // 게시판 설정 로드
    $boardData = getBoard(['where'=>$baordId]);
    if (empty($boardData) === true) {
        commonMoveAlert('존재하지 않는 게시판입니다.',BOARD_DIR.'/mypost_list.php');
    }

    // 게시글 정보 조회
    $condition = [
        'select' => '`post`.*, `bc`.title as `category_title`',
        'join' => [
            'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
        ],
        'where' => '`post`.id = "'.$postId.'" AND `post`.board_id = "'.$baordId.'"',
    ];
    $postData = getPost($condition);
    if (empty($postData) === true) {
        commonMoveAlert('존재하지 않는 게시글입니다.',BOARD_DIR.'/mypost_list.php');
    }

    // 조회수 증가 처리
    postHitIncrease(['post_id' => $postId]);

    // 비회원, 회원 게시글 분기 처리
    $isMember = $isOwn = $isMemberOwn = false;
    if ($postData['member_id']) {
        $isMemberOwn = true;
        if (isset($_SESSION['id'])) {
            $isMember = true;
            if ($postData['member_id'] === $_SESSION['id']) {
                $isOwn = true;
            }
        }
    }

    return [$postData, $isMember, $isOwn, $isMemberOwn];
}

function postHitIncrease(array $params)
{
    global $dbh;
    $hitsUpdateQuery = "UPDATE post SET hits = hits + 1 WHERE id = ".$params['post_id'];
    $dbh->query($hitsUpdateQuery);
}