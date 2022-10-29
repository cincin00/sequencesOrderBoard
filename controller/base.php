<?php

// 관리자 URL 패턴
$adminUrlPattern = '/^\/admin\//';
// 입려 URL
$subject = $_SERVER['REQUEST_URI'];
$result = preg_match($adminUrlPattern, $subject);
// 관리자or사용자 페이지 접근에 따른 기본 호출 파일 분기
if ($result) {
    require_once(__DIR__.'/admin_base.php');
} else {
    require_once(__DIR__.'/user_base.php');
}
