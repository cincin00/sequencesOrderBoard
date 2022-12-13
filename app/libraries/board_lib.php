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

    $baseQuery = "SELECT * FROM board";
    $query = queryBuilder($baseQuery, $params);
    $result = $dbh->query($query);
    if($fetchType > 0){
        $response = $result->fetchAll();
    }else{
        $response = $result->fetch();
    }

    return $response;
}

/**
 * 게시판의 전체 게시글 수량 조회
 *
 * @param int $boardId 게시판 아이디
 * @param int $isDelete 삭제된 게시글 포함 여부(0:미포함,1:포함)
 * @return int
 */
function getTotalPostNum(int $boardId, int $isDelete = 0)
{
    global $dbh;
    $response = 0;
    $query = $dbh->query('SELECT COUNT(*) as `total_cnt` FROM post WHERE board_id = '.$boardId.' AND is_delete = '.$isDelete);
    $response = $query->fetch()[0];

    return $response;
}

function getPagingData(array $params, int $boardId)
{
    // 현재 페이지 번호(기본: 1페이지)
    $currentPage = (isset($params['page']) === true ? $params['page'] : 1);
    // 게시글 전체 수량(삭제 처리되지 않은 게시글만 조회)
    $totalRow = getTotalPostNum($boardId);
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

    return [$firstPage, $prePage, $currentPage, $nextPage, $lastPage, $totalPage, $length, $startRow, $totalRow];
}

function getSinglePostData(int $postId)
{
    global $dbh;
    $response = [];
    $query = "SELECT * FROM post WHERE id = ".$postId;
    $result = $dbh->query($query);
    $response = $result->fetch();

    return $response;
}

/**
 * 게시판의 전체 게시글 조회
 *
 * @param int $id 게시글 아이디
 * @param int $isDelete 삭제된 게시글 포함 여부(0:미포함,1:포함)
 * @return array
 */
function getPostData(int $postId, int $isDelete = 0)
{
    global $dbh;
    $response = [];
    $query = "SELECT `po`.*, `bc`.title as `category_title` FROM post as `po` LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id  WHERE `po`.id = '$postId' AND `po`.is_delete = '$isDelete'";
    $result = $dbh->query($query);
    $response = $result->fetch();

    return $response;
}
/**
 * 게시판의 전체 게시글 조회
 *
 * @param int $boardId 게시판 아이디
 * @param int $startRow 조회 시작할 순서
 * @param int $length 조회할 수량
 * @param int $isDelete 삭제된 게시글 포함 여부(0:미포함,1:포함)
 * @return array
 */
function getPostDataForPaging(int $boardId, int $startRow = 0, int $length = 10, int $isDelete = 0)
{
    global $dbh;
    $response = [];
    $query = "SELECT `po`.*, `bc`.title as `category_title` FROM post as `po` LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id  WHERE `po`.board_id = '$boardId' AND `po`.is_delete = '$isDelete' ORDER BY `po`.group_id DESC, `po`.group_order ASC, `po`.group_depth DESC LIMIT ".$startRow.", ".$length."";
    $result = $dbh->query($query);
    $response = $result->fetchAll();

    return $response;
}

/**
 * 게시글 수정
 * @todo board_category Error Fix
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
    if($boardCategory){
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
    $sth = $dbh->query('SELECT COUNT(*) as `reply_num` FROM post WHERE is_delete = 0 AND group_id = '.$id);
    $queryResult = $sth->fetch();
    return $queryResult['reply_num'] > 1 ? true : false;
}

/**
 * 게시글 카테고리 조회
 *
 * @param array $params 조회 조건
 */
function getCategoryData(array $params = [], int $fetchType = 0)
{
    global $dbh;
    $response = [];
    // 카테고리 조회 기본 쿼리
    $baseQuery = "SELECT * FROM board_category";
    $query = queryBuilder($baseQuery, $params);
    $result = $dbh->query($query);
    if($fetchType > 0){
        $response = $result->fetchAll();
    }else{
        $response = $result->fetch();
    }

    return $response;
}
