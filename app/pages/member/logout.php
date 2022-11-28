<?php

require_once('../../../index.php');

session_destroy();
echo '<script>alert(`로그아웃 되었습니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';