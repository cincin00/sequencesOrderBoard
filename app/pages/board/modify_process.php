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
    } elseif (empty($params['password']) === true) {
        $msg = '비밀번호는 필수입니다.';
    } else {
        $msg = '';
    }

    if (empty($msg) === false) {
        echo '<script>alert(`'.$msg.'`); location.href = "'.BOARD_DIR.'/write.php";</script>';
        exit;
    }
    // 비밀번호 재검증
    $postId = $params['post_id'];
    $password = md5($params['password']);

    $postQuery = "SELECT * FROM post WHERE id = ".$postId;
    $postResult = $dbh->query($postQuery);
    $postData = $postResult->fetch();
    if ($postData['password'] !== $password) {
        echo '<script>alert(`게시글 수정이 실패했습니다.`);location.href = "'.BOARD_DIR.'/view.php?board_id=&'.$params['board_id'].'id='.$postId.'";</script>';
        exit;
    }

    // 데이터 가공
    $memberId = $params['member_id'];
    $title = $params['title'];
    $contents = htmlentities($params['contents']);
    $boardCategory = $params['board_category'];
    $modifyDate = date('Y-m-d H:i:s');
    // 데이터 저장
    $postQuery = "UPDATE post SET title='$title',contents='$contents',board_category=$boardCategory WHERE id = $postId";
    $result = $dbh->exec($postQuery);
    if ($result) {
        echo '<script>alert(`게시글이 수정되었습니다.`);location.href = "'.BOARD_DIR.'/view.php?board_id='.$params['board_id'].'&id='.$postId.'";</script>';
    } else {
        echo '<script>alert(`게시글 수정이 실패했습니다.`);location.href = "'.BOARD_DIR.'/view.php?board_id='.$params['board_id'].'&id='.$postId.'";</script>';
    }
