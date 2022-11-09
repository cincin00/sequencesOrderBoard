<?php
    require_once('../../../index.php');
    // 폼 전송 데이터 수신
    $params['board_id'] = (isset($_POST['board_id']) === true ? $_POST['board_id']: 0);
    $params['member_id'] = (isset($_POST['member_id']) === true ? $_POST['member_id']: NULL);
    $params['password'] = (isset($_POST['password']) === true ? $_POST['password']: '');
    $params['title'] = (isset($_POST['title']) === true ? $_POST['title']: '');
    $params['contents'] = (isset($_POST['contents']) === true ? $_POST['contents']: NULL);
    $params['board_category'] = (isset($_POST['board_category']) === true ? $_POST['board_category']: NULL);
    $params['writer'] = (isset($_POST['writer']) === true ? $_POST['writer']: '');
    //$params['regist_date'] = (isset($_POST['regist_date']) === true ? $_POST['regist_date']: '');
    //$params['is_delete'] = (isset($_POST['is_delete']) === true ? $_POST['is_delete']: '');
    //$params['hits'] = (isset($_POST['hits']) === true ? $_POST['hits']: '');
    
    // 데이터 유효성 검증
    if(empty($params['password']) === true){
        $msg = '비밀번호는 필수입니다.';
    }elseif(empty($params['title']) === true){
        $msg = '게시글 제목은 필수입니다.';
    }elseif(empty($params['contents']) === true){
        $msg = '게시글 내용은 필수입니다.';
    }elseif(empty($params['writer']) === true){
        $msg = '작성자는 필수입니다.';
    }else{
        $msg = '';
    }

    if(empty($msg) === false) {
        echo '<script>alert(`'.$msg.'`);return false;</script>';
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
    $hits = 1;

    // 데이터 저장
    // $postQuery = "INSERT INTO post ('board_id','member_id','password','title','contents','board_category','writer','regist_date','is_delete','hits') VALUES ($boardId, '$memberId', '$password', '$title', '$contents', '$boardCategory', '$writer', $registDate, $isDelete, $hits)";
    $postQuery = "INSERT INTO post (board_id,password,title,contents,board_category,writer,regist_date,is_delete,hits) VALUES ($boardId, '$password', '$title', '$contents', $boardCategory, '$writer', '$registDate', $isDelete, $hits)";
    $result = $dbh->exec($postQuery);
    if($result) {
        $postId = $dbh->lastInsertId();
        echo '<script>alert(`게시글이 등록되었습니다.`);location.href = "'.BOARD_DIR.'/view.php?id='.$postId.'";</script>';
    } else {
        echo '<script>alert(`게시글 등록이 실패했습니다.`);return false;</script>';
    }
?>