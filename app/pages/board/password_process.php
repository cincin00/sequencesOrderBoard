<?php

    require_once('../../../index.php');

    // 게시판 번호
    $boardId = (isset($_POST['board_id']) === true ? $_POST['board_id'] : 0);
    // 게시글 번호
    $postId = (isset($_POST['id']) === true ? $_POST['id'] : 0);
    // 게시글 처리방식(수정:update,삭제:delete)
    $mode = isset($_POST['mode']) === true ? $_POST['mode'] : '';
    // 게시글 비밀번호(샤용자 입력)
    $password = isset($_POST['password']) === true ? $_POST['password'] : '';

    $msg = '';
    $href = '';

    if (empty($postId) === true) {
        $msg = '존재하지 않는 게시글입니다.';
        $href = BOARD_DIR.'/list.php';
    } elseif (empty($mode) === true) {
        $msg = '잘못된 요청입니다.';
        $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
    } elseif (empty($password) === true) {
        $msg = '비밀번호는 필수입니다.';
        $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
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
    $postWhereQuery = " WHERE `po`.id = ".$postId;
    $postQuery = $postBaseQuery.$postJoinQuery.$postWhereQuery;
    $postResult = $dbh->query($postQuery);
    $postData = $postResult->fetch();

    // 공통 검증 - 게시글이 없을때, 비밀번호가 틀릴때
    if (empty($postData) === true) {
        $msg = '일치하는 게시글이 없습니다.';
        $href = BOARD_DIR.'/list.php';
    } elseif ($postData['password'] !== md5($password)) {
        $msg = '비밀번호가 올바르지 않습니다.';
        $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
    }

    if (empty($msg) === false && empty($href) === false) {
        echo '<script>alert(`'.$msg.'`); location.href = "'.$href.'";</script>';
        exit;
    }

    if ($mode === 'update') {
        // 게시판 카테고리 조회
        $categoryQuery = "SELECT * FROM board_category ORDER BY sort_order";
        $categoryResult = $dbh->query($categoryQuery);
        foreach ($categoryResult as $categoryData) {
            $category[$categoryData['sort_order']] = [
                'category_id' => $categoryData['id'],
                'title' => $categoryData['title']
            ];
        }
        // 수정 페이지 호출
        include_once(BASEPATH.'/app/pages/board/modify.php');
    } elseif ($mode === 'delete') {
        // 삭제 처리
        $deleteQuery = 'UPDATE post SET is_delete = 1 WHERE id ='.$postId;
        $queryResult = $dbh->exec($deleteQuery);
        if ($queryResult) {
            $msg = '게시글이 삭제되었습니다.';
            $href = BOARD_DIR.'/list.php';
        } else {
            $msg = '게시글 삭제가 실패했습니다.';
            $href = BOARD_DIR.'/view.php?id='.$id;
        }
        echo '<script>alert(`'.$msg.'`); location.href = "'.$href.'";</script>';
    } else {
        echo '<script>alert(`잘못된 요청입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
        exit;
    }
