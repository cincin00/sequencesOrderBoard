<?php

/**
 * mypage 영역에서 로그인 여부 확인
 * 
 */
function checkLogin()
{
    $haveId = isset($_SESSION['id']) === true ? true : false;
    if($haveId === false) {
        $msg = '로그인이 필요한 서비스입니다.';
        $location = MEMBER_DIR.'/login.php';
        
        commonMoveAlert($msg, $location);
    }
}