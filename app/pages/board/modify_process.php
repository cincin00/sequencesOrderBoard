<?php

    require_once('../../../index.php');

    // 폼 전송 데이터 수신
    $params['board_id'] = (isset($_POST['board_id']) === true ? $_POST['board_id'] : 0);
    $params['post_id'] = (isset($_POST['post_id']) === true ? $_POST['post_id'] : 0);
    $params['member_id'] = (isset($_POST['member_id']) === true ? $_POST['member_id'] : null);
    $params['password'] = (isset($_POST['password']) === true ? $_POST['password'] : '');
    $params['title'] = (isset($_POST['title']) === true ? $_POST['title'] : '');
    $params['contents'] = (isset($_POST['contents']) === true ? $_POST['contents'] : null);

    if (isset($_POST['board_category']) === false) {
        $params['board_category'] = 'NULL';
    } elseif (empty($_POST['board_category']) === true) {
        $params['board_category'] = 'NULL';
    } else {
        $params['board_category'] = $_POST['board_category'];
    }
    // 데이터 유효성 검증
    if (empty($params['title']) === true) {
        $msg = '게시글 제목은 필수입니다.';
    } elseif (empty($params['contents']) === true) {
        $msg = '게시글 내용은 필수입니다.';
    } elseif (empty($params['password']) === true && empty($params['member_id']) === true) {
        $msg = '비밀번호는 필수입니다.';
    } else {
        $msg = '';
    }

    if (empty($msg) === false) {
        commonMoveAlert($msg, BOARD_DIR.'/write.php');
    }

    // 비밀번호 재검증
    $postId = $params['post_id'];
    $password = md5($params['password']);
    $memberId = $params['member_id'];
    $postData = getSinglePostData($postId);

    if ($postData['member_id']) {
        // 회원 게시글
        if ((int)$memberId !== $postData['member_id']) {
            // 회원일떄, 본인 게시글 검증
            commonMoveAlert('게시글 수정이 권한이 없습니다.', BOARD_DIR.'/view.php?board_id='.$params['board_id'].'&id='.$postId);
        }
    } else {
        // 비회원 게시글
        if ($postData['password'] !== $password) {
            // 비회원일때, 비밀번호 검증
            commonMoveAlert('게시글 수정이 실패했습니다.', BOARD_DIR.'/view.php?board_id='.$params['board_id'].'&id='.$postId);
        }
    }

    // 데이터 저장
    $params['post_id'] = $postId;
    $result = modifyPost($params);
    if ($result) {
        commonMoveAlert('게시글이 수정되었습니다.',BOARD_DIR.'/view.php?board_id='.$params['board_id'].'&id='.$postId);
    } else {
        commonMoveAlert('게시글 수정이 실패했습니다.',BOARD_DIR.'/view.php?board_id='.$params['board_id'].'&id='.$postId);        
    }
