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
    if($fetchType > 0){
        $response = $result->fetchAll();
    }else{
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
    $rowParams = [
        'select' => 'COUNT(*) as `total_cnt`',
        'where' => 'board_id = '.$boardId.' AND is_delete = 0'
    ];
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
    if($fetchType > 0){
        $response = $result->fetchAll();
    }else{
        $response = $result->fetch();
    }

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
    $query = 'SELECT COUNT(*) as `reply_num` FROM post WHERE is_delete = 0 AND group_id = '.$id;
    $sth = $dbh->query($query);
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
    $table = "board_category";
    $query = queryBuilder($table, $params);
    $result = $dbh->query($query);
    if($fetchType > 0){
        $response = $result->fetchAll();
    }else{
        $response = $result->fetch();
    }

    return $response;
}
