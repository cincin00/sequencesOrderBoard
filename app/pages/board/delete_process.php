<?php

    require_once('../../../index.php');

    $id = 0;
    $passwd = '';
    $msg = '';
    $href = '';

    // 폼데이터 수신 및 유효성 검증
    if (isset($_POST['id']) === true && empty($_POST['id']) === false) {
        $id = $_POST['id'];
    } else {
        $msg = '게시글 정보가 존재하지 않습니다.';
        $href = BOARD_DIR.'/list.php';
    }

    if (isset($_POST['password']) === true && empty($_POST['password']) === false) {
        $passwd = md5($_POST['password']);
    } else {
        $msg = '게시글 비밀번호는 필수입니다.';
        $href = BOARD_DIR.'/view.php?id='.$id;
    }

    // 게시글 조회
    $boardBaseQuery = 'SELECT * FROM post';
    $boardWhereQuery = ' WHERE id = '.$id;
    $boardQuery = $boardBaseQuery.$boardWhereQuery;
    $boardResult = $dbh->query($boardQuery);
    $boardData = $boardResult->fetch();

    if (empty($boardData) === true) {
        $msg = '삭제하려는 게시글이 없습니다.';
        $href = BOARD_DIR.'/view.php?id='.$id;
    } elseif ($passwd !== $boardData['password']) {
        $msg = '비밀번호가 올바르지 않습니다.';
        $href = BOARD_DIR.'/view.php?id='.$id;
    }

    if ($msg && $href) {
        $response = [
            'result' => false,
            'msg' => $msg,
            'href' => $href,
        ];
    } else {
        // 게시글 삭제
        $deleteQuery = 'DELETE FROM post WHERE id='.$id;
        $queryResult = $dbh->exec($deleteQuery);
        if ($queryResult) {
            $msg = '게시글이 삭제되었습니다.';
            $href = BOARD_DIR.'/list.php';
            $result = true;
        } else {
            $msg = '게시글 삭제가 실패했습니다.';
            $href = BOARD_DIR.'/view.php?id='.$id;
            $query = false;
        }
        $response = [
            'result' => $result,
            'msg' => $msg,
            'href' => $href,
        ];
    }
    echo json_encode($response);
