<?php

require_once('../../../index.php');

session_destroy();
commonMoveAlert('로그아웃 되었습니다.', MEMBER_DIR.'/login.php');