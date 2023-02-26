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
    // 검색어
    $keyword = (isset($params['keyword']) === true ? htmlspecialchars($params['keyword']) : '');
    // 게시글 전체 수량(삭제 처리되지 않은 게시글만 조회)
    if (validSingleData($params, 'row_params')) {
        $rowParams = $params['row_params'];
    } else {
        $rowParams = [
            'select' => 'COUNT(*) as `total_cnt`',
            'where' => $boardId.' AND is_delete = 0 AND title LIKE "%'.$keyword.'%" OR contents LIKE "%'.$keyword.'%" OR writer LIKE "%'.$keyword.'%"',
            'debug' => false
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
    // $block = 5;
    // 현재 블록 번호 ( 현재 페이지 / 블록당 페이지 수 )
    //$currentBlock = ceil($currentPage / $block);
    // 전체 블록 번호 ( 전체 페이지 / 블록당 페이지 수 )
    //$totalBlock = ceil($totalPage / $block);

    return [$firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow, $keyword];
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
    // URL 데이터 정재
    $params = validDuplicationData($params);
    // 페이징 처리 (처음페이지, 이전페이지, 현재페이지, 다음페이지, 마지막페이지, 전체페이지, 페이지당 게시글 수, 시작 게시글, 전체 게시글)
    list($firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow, $keyword) = getPagingData($params, 1);
    // 게시글 정보 조회 - 계층형 게시글 정렬 및 페이징 처리
    $params = [
        'select' => '`post`.*, `bc`.title as `category_title`',
        'join' => [
            'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
        ],
        'where' => '`post`.board_id = 1 AND `post`.is_delete = 0 AND (`post`.title LIKE "%'.$keyword.'%" OR `post`.contents LIKE "%'.$keyword.'%" OR `post`.writer LIKE "%'.$keyword.'%")',
        'orderby' => '`post`.group_id DESC, `post`.group_order ASC, `post`.group_depth DESC',
        'limit' => $startRow.", ".$length,
        'debug' => false
      ];
    $postData = getPost($params, 1);

    // 페이징 데이터 가공
    $baseUrl = BOARD_DIR.'/list.php?';
    $fpu = (empty($firstPage) == false ? 'page='.$firstPage : '');
    $ppu = (empty($prePage) == false ? 'page='.$prePage : '');
    $npu = (empty($nextPage) == false ? 'page='.$nextPage : '');
    $lpu = (empty($lastPage) == false ? 'page='.$lastPage : '');
    $key = (empty($keyword) === false ? '&keyword='.$keyword : '');

    for($i=1;$i<=$totalPage;$i++){
        $currentPageUrl[] = $baseUrl.'page='.$i.$key;
    }
    $firstPageUrl = $baseUrl.$fpu.$key;
    $prePageUrl = $baseUrl.$ppu.$key;
    $nextPageUrl = $baseUrl.$npu.$key;
    $lastPageUrl = $baseUrl.$lpu.$key;

    return [$boardData, $postData, $firstPageUrl, $prePageUrl, $currentPage, $nextPageUrl, $lastPageUrl, $totalPage, $length, $startRow, $totalRow, $currentPageUrl];
}

/**
 * 게시판 목록 조회 함수
 *
 * @description
 * - 반환 데이터: 게시판 정보, 게시글 정보, 페이징 정보
 * - 목록 페이지에 페이징을 위한 데이터: [처음], [이전], [페이지 시작 번호], [블록당 페이지 번호], [페이지 끝 번호], [다음], [마지막]
 */
function getPostForListTmp($params)
{
    /**
     * ------------------------------------
     * 검색어(keyword):
     * 검색 대상(target):
     * ------------------------------------
     * 페이지당 게시글 수(length - limit Condtion): 10, 20, 30, 40, 50, 100
     * 전체 게시글 수(total_post): SELECT COUNT(*) FROM post WHERE is_delete = 0 AND
     * ------------------------------------
     * 전체 페이지(total_page): ceil(total_post/length)
     * ------------------------------------
     * 블록 당 페이지 수(block): 3, 6, 9, 12, 15, 27
     * 전체 블록 수(total_block): ceil(total_page/block)
     * 시작 페이지 번호(start_page): 1
     * 시작 블록 페이지 번호(start_block_page - (해당 글의 블럭번호 - 1) * 블럭당 페이지 수 + 1): ( current_block - 1) * block + 1
     * 현재 페이지(current_page): isset($GET['page']) == true ? (empty($_GET['page']) === false ? $_GET['page'] : start_page ) : start_page
     * ------------------------------------
     * 시작 게시글 번호(start_post_num - limit Condtion: (현재 페이지 번호 - 1) * 페이지 당 표시할 게시글 수  ): (current_page - 1) * length
     * ------------------------------------
     * 현재 블록 번호(current_block-현재 페이지 번호 / 블럭 당 페이지 수): ceil(current_page / block)
     * 종료 블록 페이지 번호(end_block_page - 현재 블럭 번호 * 블럭 당 페이지 수): current_blcok * block
     * 이전 페이지 번호(prev_page):
     * 다음 페이지 번호(next_page):
     * 종료 페이지 번호(end_page): total_page
     * ------------------------------------
     * 정렬 대상(orderby): id, title, board_category, regist_date, hits
     * 정렬 방식(orderby): asc, desc
     * ------------------------------------
     * 게시판 번호(board_id):
     */

    global $dbh;

    // 게시판 순번
    $boardId = isset($_GET['board_id']) === true ? $_GET['board_id'] : 1;
    // 검색어 - Main
    $keyword = isset($_GET['keyword']) === true ? $_GET['keyword'] : '';
    // 검색 컬럼명 - Sub
    $target = isset($_GET['target']) === true ? $_GET['target'] : 'all';
    // 정렬 대상(id, title, board_category, regist_date, hits / ASC, DESC)
    $orderbyTmp = isset($_GET['orderby']) === true ? $_GET['orderby'] : 'id|desc';
    // 현재 페이지 번호
    $currentPage = isset($_GET['page']) === true ? $_GET['page'] : 1;
    // 페이지당 표시 게시글
    $length = isset($_GET['length']) === true ? $_GET['length'] : 10;
    // 시작 게시글 번호
    $startPostNum = ($currentPage -1) * $length;
    // 전체 게시글 수 조회 질의문
    $select = 'SELECT post.*, board_category.*';
    $from = ' FROM post';
    $join = ' LEFT JOIN board_category ON post.board_id = board_category.board_id';
    $where = ' WHERE post.is_delete = 0 AND post.board_id = '.$boardId;
    if (empty($keyword) === false) {
        if ($target === 'title') {
            $where .= ' AND post.title LIKE "%'.$keyword.'%"';
        } elseif ($target === 'contents') {
            $where .= ' AND post.contents LIKE "$'.$keyword.'%"';
        } else {
            $where .= ' AND post.title LIKE "%'.$keyword.'%" AND post.contents LIKE "%'.$keyword.'%"';
        }
    }
    $tmp = explode('|', $orderbyTmp);
    $orderby = ' ORDER BY post.'.$tmp[0].' '.$tmp[1];
    $limit = ' LIMIT '.$startPostNum.', '.$length;
    $totalPageSql = $select.$from.$where;
    $limitPageSql = $select.$from.$join.$where.$orderby.$limit;
    $totalQuery = $dbh->query($totalPageSql);
    $limitQuery = $dbh->query($limitPageSql);
    $totalRes = $totalQuery->fetchAll();
    $limitRes = $limitQuery->fetchAll();
    dd($totalPageSql);
    $totalPostNum = count($totalRes);
    // 전체 페이지
    $totalPage = ceil($totalPostNum/$length);
    // 블록당 페이지 수
    $block = isset($_GET['block']) === true ? $_GET['block'] : 3;
    // 현재 블록 번호
    $currentBlock = ceil($currentPage/$block);
    // 전체 블록 번호
    $totalBlock = ceil($totalPage/$block);

    var_dump('currentPage:::'.$currentPage);
    var_dump('length:::'.$length);
    var_dump('startPostNum:::'.$startPostNum);
    var_dump('totalPostNum:::'.$totalPostNum);
    var_dump('block:::'.$block);
    var_dump('currentBlock:::'.$currentBlock);
    var_dump('totalBlock:::'.$totalBlock);
    var_dump($limitRes);
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
    // 검색어
    $keyword = (isset($params['keyword']) === true ? htmlspecialchars($params['keyword']) : '');    
    $keywordCondtion = (empty($keyword) === false ? ' AND (`post`.title LIKE "%'.$keyword.'%" OR `post`.contents LIKE "%'.$keyword.'%" OR `post`.writer LIKE "%'.$keyword.'%")' : '');
    $params['row_params'] = [
        'select' => 'COUNT(*) as `total_cnt`',
        'where' => 'board_id = '.$boardId.' AND is_delete = 0 AND member_id = '.$_SESSION['id'].$keywordCondtion,
        //'debug' => true,
    ];
    // 페이징 처리
    list($firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow, $keyword) = getPagingData($params, 1);
    // 게시글 정보 조회 - 계층형 게시글 정렬 및 페이징 처리
    $params = [
      'select' => '`post`.*, `bc`.title as `category_title`',
      'join' => [
          'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
      ],
      'where' => '`post`.board_id = 1 AND `post`.is_delete = 0 AND `post`.member_id = '.$_SESSION['id'].$keywordCondtion,
      'orderby' => '`post`.group_id DESC, `post`.group_order ASC, `post`.group_depth DESC',
      'limit' => $startRow.", ".$length,
      //'debug' => true,
    ];
    $postData = getPost($params, 1);

    // 페이징 데이터 가공
    $baseUrl = BOARD_DIR.'/mypost_list.php?';
    $fpu = (empty($firstPage) == false ? 'page='.$firstPage : '');
    $ppu = (empty($prePage) == false ? 'page='.$prePage : '');
    $npu = (empty($nextPage) == false ? 'page='.$nextPage : '');
    $lpu = (empty($lastPage) == false ? 'page='.$lastPage : '');
    $key = (empty($keyword) === false ? '&keyword='.$keyword : '');

    for($i=1;$i<=$totalPage;$i++){
        $currentPageUrl[] = $baseUrl.'page='.$i.$key;
    }
    $firstPageUrl = $baseUrl.$fpu.$key;
    $prePageUrl = $baseUrl.$ppu.$key;
    $nextPageUrl = $baseUrl.$npu.$key;
    $lastPageUrl = $baseUrl.$lpu.$key;

    return [$boardId, $postData,  $firstPageUrl, $prePageUrl, $currentPage, $nextPageUrl, $lastPageUrl, $totalPage, $length, $startRow, $totalRow, $currentPageUrl];
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
 * 게시글 수정
 * @todo board_category Error Fix
 * @param array $params
 * @return object
 */
function modifyPost2(array $params)
{
    global $dbh;
    $response = false;

    // 수정 처리 시 변경 값은 필수값
    if (validSingleData($params, 'set')) {
        $query = 'UPDATE post SET '.$params['set'];
    } else {
        commonAlert('게시글 수정 실패.');
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
        commonMoveAlert('존재하지 않는 게시판입니다.', BOARD_DIR.'/list.php');
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
        commonMoveAlert('존재하지 않는 게시판입니다.', BOARD_DIR.'/list.php');
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
        commonMoveAlert('존재하지 않는 게시글입니다.', BOARD_DIR.'/list.php');
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
        commonMoveAlert('존재하지 않는 게시판입니다.', BOARD_DIR.'/mypost_list.php');
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
        commonMoveAlert('존재하지 않는 게시글입니다.', BOARD_DIR.'/mypost_list.php');
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
