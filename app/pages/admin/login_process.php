<?php
    require_once('../../../index.php');

    $adminId = isset($_POST['admin_id']) === true ? $_POST['admin_id'] : '';
    $adminPw = isset($_POST['admin_pw']) === true ? md5($_POST['admin_pw']) : '';
    $params = [];

    if(empty($adminId) === true || empty($adminPw) === true){
        commonMoveAlert('관리자 계정 정보를 입력해주세요.', ADMIN_DIR.'/login.php');
    }else{
        $params = ['where' => 'account_id = "'.$adminId.'" AND account_password = "'.$adminPw.'"'];
        $adminData = getAdminData($params);
        
        if(empty($adminData)){
            $msg = '관리자 정보를 확인해주세요.';
            $location = ADMIN_DIR.'/login.php';
        }else{
            $msg = '관리자 로그인 되었습니다.';
            $location = ADMIN_DIR.'/index.php';
            $sessionData = [
                'admin_id' => $adminData['id'],
                'admin_id' => $adminData['account_id'],
                'admin_name' => $adminData['name'],
            ];
            setAdminSession($sessionData);
        }

        commonMoveAlert($msg, $location);
    }

?>