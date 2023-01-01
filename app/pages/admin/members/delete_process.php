<?php
    
    require_once('../../../../index.php');
    require_once('../../../libraries/member_lib.php');
    require_once('../../../libraries/admin_lib.php');
    
    $memberId = (isset($_GET['member_id']) === true ? $_GET['member_id']:0);
    $mode = (isset($_GET['mode']) === true ? $_GET['mode']:1);
    if(empty($memberId)){
        commonMoveAlert('올바르지 않은 회원 정보입니다.', ADMIN_DIR.'/members/list.php');
    }

    $updateCondtion = [
        'set' => 'withdrawal = '.$mode,
        'where' => 'id = "'.$memberId.'"'
        //,'debug' => true
    ];
    $result = updateMember($updateCondtion);
    if($mode){
        $word = '탈퇴';
    }else{
        $word = '복구';
    }
    
    if($result){
        $msg = '회원이 '.$word.' 처리되었습니다.';
        $href = ADMIN_DIR.'/members/view.php?member_id='.$memberId;
    }else{
        $msg = $word.' 처리가 실패되었습니다.';
        $href = ADMIN_DIR.'/members/view.php?member_id='.$memberId;
    }

    commonMoveAlert($msg, $href);
?>