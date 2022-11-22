<?php
// 기본 경로: /opt/homebrew/var/www/board01/public_html
define('BASEPATH', __DIR__);
define('CONFIGPATH', __DIR__.'/app/config');
// 상수 정의 파일 호출
require_once(CONFIGPATH.'/config.php');
// 데이터베이스 연결
require_once(CONFIGPATH.'/database.php');

session_start();
$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
