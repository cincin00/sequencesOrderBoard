<?php

    require_once('../../../index.php');

    // 게시글 번호
    $id = (isset($_POST['post_id']) === true ? $_POST['post_id'] : 0);
    // 게시글 처리방식(수정:update,삭제:delete)
    $mode = isset($_POST['mode']) === true ? $_POST['mode'] : '';
    // 게시글 비밀번호(샤용자 입력)
    $password = isset($_POST['password']) === true ? $_POST['password'] : '';

    $msg = '';
    $href = '';

    if (empty($id) === true) {
        $msg = '존재하지 않는 게시글입니다.';
        $href = BOARD_DIR.'/list.php';
    } elseif (empty($mode) === true) {
        $msg = '잘못된 요청입니다.';
        $href = BOARD_DIR.'/view.php?id='.$id;
    } elseif (empty($password) === true) {
        $msg = '비밀번호는 필수입니다.';
        $href = BOARD_DIR.'/view.php?id='.$id;
    }

    if (empty($msg) === false && empty($href) === false) {
        echo '<script>alert(`'.$msg.'`); location.href = "'.$href.'";</script>';
        exit;
    }

    // 게시판 설정 조회
    $boardQuery = "SELECT * FROM board WHERE id='1'";
    $boardResult = $dbh->query($boardQuery);
    $boardData = $boardResult->fetch();
    // 게시글 정보 조회
    $postBaseQuery = "SELECT `po`.id, `po`.title , `po`.writer, `po`.contents,`po`.password, `po`.regist_date, `po`.hits, `bc`.id as `category_id`, `bc`.title as `category_title` FROM post as `po`";
    $postJoinQuery = " LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id";
    $postWhereQuery = " WHERE `po`.id = ".$id;
    $postQuery = $postBaseQuery.$postJoinQuery.$postWhereQuery;
    $postResult = $dbh->query($postQuery);
    $postData = $postResult->fetch();
    // 게시판 카테고리 조회
    $categoryQuery = "SELECT * FROM board_category ORDER BY sort_order";
    $categoryResult = $dbh->query($categoryQuery);
    foreach ($categoryResult as $categoryData) {
        $category[$categoryData['sort_order']] = [
            'category_id' => $categoryData['id'],
            'title' => $categoryData['title']
        ];
    }

    // 공통 검증 - 게시글이 없을때, 비밀번호가 틀릴때
    if (empty($postData) === true) {
        $msg = '일치하는 게시글이 없습니다.';
        $href = BOARD_DIR.'/list.php';
    } elseif ($postData['password'] !== md5($password)) {
        $msg = '비밀번호가 올바르지 않습니다.';
        $href = BOARD_DIR.'/view.php?id='.$id;
    }

    if (empty($msg) === false && empty($href) === false) {
        echo '<script>alert(`'.$msg.'`); location.href = "'.$href.'";</script>';
        exit;
    }

    if ($mode === 'update') {
        // 수정 페이지 호출
        include_once(BASEPATH.'/app/pages/board/modify.php');
    } elseif ($mode === 'delete') {
        // 삭제 처리
    }else{
        echo '<script>alert(`잘못된 요청입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
        exit;
    }
