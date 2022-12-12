<?php
// 기본 경로: /opt/homebrew/var/www/board01/public_html
define('BASEPATH', __DIR__);
define('CONFIGPATH', __DIR__.'/app/config');
// 상수 정의 파일 호출
require_once(CONFIGPATH.'/config.php');
// 데이터베이스 연결
require_once(CONFIGPATH.'/database.php');
// 로그인 세션 관리
session_start();
$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
// 라이브러리 관리 - 폴더 별 라이브러리 추가!
// /app/pages/board/view.php?board_id=1&id=73
$subject = $_SERVER['REQUEST_URI'];
$pattern = '/app\/pages\/[a-zA-Z]*\/[a-zA-Z|_]*.php/smi';
// fail: 0 or false, success: array
preg_match($pattern, $subject, $matches);
var_dump($pattern, $subject,$matches);
if(empty($matches) === false){
    // URL은 1개만 전달되므로 0번째 고정 - e.g) /app/pages/board/view.php
    $requestUri = explode('/',$matches[0]);
    switch($requestUri[2]){
        case 'board':
            require_once(BASEPATH.'/app/libraries/board_lib.php');
            break;
        case 'member':
            require_once(BASEPATH.'/app/libraries/member_lib.php');
            break;
        default:
            require_once(BASEPATH.'/app/libraries/common_lib.php');
    }
}else{
    require_once(BASEPATH.'/app/libraries/common_lib.php');
}
