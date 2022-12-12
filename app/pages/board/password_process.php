<?php

    require_once('../../../index.php');

    // 게시판 번호
    $boardId = (isset($_POST['board_id']) === true ? $_POST['board_id'] : 0);
    // 게시글 번호
    $postId = (isset($_POST['id']) === true ? $_POST['id'] : 0);
    // 게시글 처리방식(수정:update,삭제:delete)
    $mode = isset($_POST['mode']) === true ? $_POST['mode'] : '';
    // 게시글 비밀번호(사용자 입력)
    $password = isset($_POST['password']) === true ? $_POST['password'] : '';
    $memberId = isset($_SESSION['id']) === true ? $_SESSION['id'] : '';

    $msg = $href = '';
    if (empty($postId) === true) {
        $msg = '존재하지 않는 게시글입니다.';
        $href = BOARD_DIR.'/list.php';
    } elseif (empty($mode) === true) {
        $msg = '잘못된 요청입니다.';
        $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
    } elseif (empty($password) === true && empty($memberId) === true) {
        $msg = '비밀번호는 필수입니다.';
        $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
    }

    if (empty($msg) === false && empty($href) === false) {
        commonMoveAlert($msg, $href);
    }

    // 게시판 설정 조회
    $boardData = getBoardSetting(1);

    // 게시글 정보 조회
    $postData = getPostData($postId);

    // 공통 검증 - 게시글이 없을때, 비밀번호가 틀릴때
    if (empty($postData) === true) {
        $msg = '일치하는 게시글이 없습니다.';
        $href = BOARD_DIR.'/list.php';
    }
    // 회원 게시글인 경우, 비회원 게시글인 경우
    if ($postData['member_id']) {
        // 회원일때, 자기 게시글 아닐떄
        if ($memberId !== $postData['member_id']) {
            $msg = '접근 권한이 없습니다.';
            $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
        }
    } else {
        // 비회원일때, 비밀번호 틀렸을때
        if ($postData['password'] !== md5($password)) {
            $msg = '비밀번호가 올바르지 않습니다.';
            $href = BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId;
        }
    }

    if (empty($msg) === false && empty($href) === false) {
        commonMoveAlert($msg, $href);
    }

    // 로그인 검증
    $isLogin = $ownPost = false;
    if (isset($_SESSION['id']) === true) {
        $isLogin = true;
        if ($memberId === $postData['member_id']) {
            $ownPost = true;
        }
    }

    if ($mode === 'update') {
        // 답글 검사
        $isReply = havePostReplay((int)$postData['id']);
        if ($isReply) {
            commonMoveAlert('답글이 있는 경우 수정 할 수 없습니다.', BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId);
        }
    } else {
        // 답글 검사
        $isReply = havePostReplay((int)$postData['id']);
        if ($isReply) {
            commonMoveAlert('답글이 있는 경우 삭제 할 수 없습니다.', BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId);
        }
    }

    if ($mode === 'update') {
        // 게시판 카테고리 조회
        $categoryResult = getCategoryData(['where'=>'board_id = '.$boardId, 'orderby'=>'sort_order']);
        foreach ($categoryResult as $key) {
            $category[$key['sort_order']] = [
                'category_id' => $key['id'],
                'title' => $key['title']
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
        commonMoveAlert($msg, $href);
    } else {
        commonMoveAlert('잘못된 요청입니다.', BOARD_DIR.'/list.php');
    }
