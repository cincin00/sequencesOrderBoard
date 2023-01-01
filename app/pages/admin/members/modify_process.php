<?php

      require_once('../../../../index.php');
      require_once('../../../libraries/member_lib.php');
      require_once('../../../libraries/admin_lib.php');

      // 폼 전송 데이터 수신      
      $params['member_id'] = (isset($_POST['member_id']) === true ? $_POST['member_id'] : '');
      $params['account_password'] = (isset($_POST['account_password']) === true ? $_POST['account_password'] : '');
      $params['account_name'] = (isset($_POST['account_name']) === true ? $_POST['account_name'] : '');
      $params['account_email'] = (isset($_POST['account_email']) === true ? $_POST['account_email'] : null);

      if ($params['account_password']) {
      }
      // 이메일 유효 검증
      if ($params['account_email']) {
          $emailRegResult = validEmail($params['account_email']);
          if ($emailRegResult == false) {
              commonMoveAlert('올바른 이메일 형식으로 입력해주세요.', ADMIN_DIR.'/members/view.php?member_id='.$params['member_id']);
          }
          // 이메일 중복 검사
          $condition = ['where' => 'email = "'.$params['account_email'].'" AND id <> "'.$params['member_id'].'"'];
          $result = getMember($condition);
          if ($result) {
              commonMoveAlert('가입된 이메일입니다.', ADMIN_DIR.'/members/view.php?member_id='.$params['member_id']);
          }          
      }

      $setData = 'email = "'.$params['account_email'].'", name = "'.$params['account_name'].'"';
      if($params['account_password']){
        $setData .= ', account_password = "'.md5($params['account_password']).'"';
      }
      $updateParams = [
        'set' => $setData,
        'where' => 'id = '.$params['member_id']
      ];

      $result = updateMember($updateParams);
      if($result){
        $msg = '회원 정보가 수정되었습니다.';
        $href = ADMIN_DIR.'/members/view.php?member_id='.$params['member_id'];
      }else{
        $msg = '회원 정보 수정에 실패하였습니다.';
        $href = ADMIN_DIR.'/members/view.php?member_id='.$params['member_id'];
      }
      commonMoveAlert($msg, $href);