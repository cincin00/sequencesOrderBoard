<?php

    require_once('../../../index.php');

    // 폼 전송 데이터 수신
    $params['board_id'] = (isset($_POST['board_id']) === true ? $_POST['board_id'] : 0);
    $params['member_id'] = (isset($_POST['member_id']) === true ? $_POST['member_id'] : null);
    $params['password'] = (isset($_POST['password']) === true ? $_POST['password'] : '');
    $params['title'] = (isset($_POST['title']) === true ? $_POST['title'] : '');
    $params['contents'] = (isset($_POST['contents']) === true ? $_POST['contents'] : null);

    $params['group_id'] = (isset($_POST['group']) === true ? $_POST['group'] : null);
    $params['group_depth'] = (isset($_POST['depth']) === true ? $_POST['depth'] : null);
    $params['group_order'] = (isset($_POST['order']) === true ? $_POST['order'] : null);

    if (isset($_POST['board_category']) === true && empty($_POST['board_category']) === false && gettype($_POST['board_category'] === 'int')) {
        $params['board_category'] = $_POST['board_category'];
    } else {
        $params['board_category'] = NULL;
    }
    $params['writer'] = (isset($_POST['writer']) === true ? $_POST['writer'] : '');

    // 데이터 유효성 검증
    if (empty($params['title']) === true) {
        $msg = '게시글 제목은 필수입니다.';
    } elseif (empty($params['contents']) === true) {
        $msg = '게시글 내용은 필수입니다.';
    } elseif (empty($params['writer']) === true && empty($params['member_id']) === true) {
        $msg = '작성자는 필수입니다.';
    } elseif (empty($params['password']) === true && empty($params['member_id']) === true) {
        $msg = '비밀번호는 필수입니다.';
    } else {
        $msg = '';
    }

    if (empty($msg) === false) {
        echo '<script>alert(`'.$msg.'`); location.href = "'.BOARD_DIR.'/write.php";</script>';
        exit;
    }

    // 데이터 가공
    $boardId = $params['board_id'];
    $memberId = $params['member_id'];
    $password = md5($params['password']);
    $title = $params['title'];
    $contents = htmlentities($params['contents']);
    $boardCategory = $params['board_category'];
    $writer = $params['writer'];
    $registDate = date('Y-m-d H:i:s');
    $isDelete = 0;
    $hits = 0;

    if(isset($params['member_id']) === true && ($params['member_id'] === $_SESSION['account_id']) ){
        $memberId = $_SESSION['id'];
        $writer = $_SESSION['account_id'];
        $password = $_SESSION['account_password'];
    }

    // 답글 데이터 처리 - 이전 게시글 번호가 있는 경우
    if (empty($params['group_id']) === false && $params['group_id'] > 0) {
        $groupId = $params['group_id'];
        // 해당 그룹에서 가장 order_seq가 높?낮?
        $groupOrder = (int)$params['group_order']+1;
        // 현재 기준이 되는 게시글의 Depth + 1
        $groupDepth = (int)$params['group_depth']+1;
    } else {
        // 원본 게시글
        $groupOrder = 0;
        $groupDepth = 0;
    }

    // 데이터 저장    
    $sth = $dbh->prepare("INSERT INTO post (board_id,member_id, password,title,contents,board_category,writer,regist_date,is_delete,hits,group_order,group_depth) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $result = $sth->execute([$boardId, $memberId, $password, $title, $contents, $boardCategory, $writer, $registDate, $isDelete, $hits, $groupOrder, $groupDepth]);
    if ($result) {
        $postId = $dbh->lastInsertId();
        // 최상위 게시글은 게시글 그룹을 자기 자신의 post.id(PK)로 할당 - $params['group_id']가 없으면 최상위
        if (empty($params['group_id']) === true && $params['group_id'] <= 0) {
            $dbh->exec("UPDATE post SET group_id=".$postId." WHERE id=".$postId);
        } else {
            $dbh->exec("UPDATE post SET group_id=".$groupId." WHERE id=".$postId);
            // 게시글 순서 갱신 - 같은 게시글 그룹 내에서 순서 1증감한 자기 자신의 순서와 같거나 큰 게시글의 순서 모두 +1 씩 증감 처리
            $dbh->exec("UPDATE post SET group_order =  group_order + 1  WHERE group_id=".$groupId." AND group_order >=".$groupOrder);
        }
        echo '<script>alert(`게시글이 등록되었습니다.`);location.href = "'.BOARD_DIR.'/view.php?board_id='.$boardId.'&id='.$postId.'";</script>';
    } else {
        echo '<script>alert(`게시글 등록이 실패했습니다.`);return false;</script>';
    }
