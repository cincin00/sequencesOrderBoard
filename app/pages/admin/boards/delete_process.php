<?php
    require_once('../../../../index.php');
    require_once('../../../libraries/board_lib.php');
    require_once('../../../libraries/admin_lib.php');
    
    $postId = (isset($_GET['post_id']) === true ? $_GET['post_id']:0);
    
    if(empty($postId)){
        commonMoveAlert('올바르지 않은 게시글 정보입니다.', ADMIN_DIR.'/boards/list.php');
    }

    $modifyCondition = [
        'set' => 'is_delete = 1',
        'where' => 'id = '.$postId
    ];
    $result = modifyPost2($modifyCondition);

    if($result){
        $msg = '게시글이 삭제처리되었습니다.';
    }else{
        $msg = '게시글 삭제를 실패했습니다.';
    }

    commonMoveAlert($msg, ADMIN_DIR.'/boards/view.php?post_id='.$postId)
?>