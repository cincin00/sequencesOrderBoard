<?php

  require_once('../../../index.php');

  $mode = (isset($_POST['mode']) === true ? $_POST['mode'] : '');

  if (empty($mode) === true) {
      commonMoveAlert('올바르지 않는 요청입니다.', MEMBER_DIR.'/login.php');
  } elseif ($mode === 'signin') {
      $accountId = (isset($_POST['account_id']) === true ? $_POST['account_id'] : '');
      $accountPassword1 = (isset($_POST['account_password1']) === true ? md5($_POST['account_password1']) : '');
      $params = ['where' => 'account_id = "'.$accountId.'" AND account_password = "'.$accountPassword1.'"'];
      $memberData = getMember($params);
      // 게시판 페이지 이동
      $location = MEMBER_DIR.'/login.php';
      if (empty($memberData) === true) {
          $msg = '등록되지않은 회원이거나 회원 정보가 틀렸습니다.';
      } elseif ($memberData['withdrawal']) {
          $msg = '탈퇴한 회원입니다.';
      } else {
          // 회원 세션 저장
          setMemberSession($memberData);
          $msg = '로그인되었습니다.';
          $location = BOARD_DIR.'/list.php';
      }
      commonMoveAlert($msg, $location);
  } elseif ($mode === 'signup') {
      $accountId = (isset($_POST['account_id']) === true ? $_POST['account_id'] : '');
      $accountPassword1 = (isset($_POST['account_password1']) === true ? md5($_POST['account_password1']) : '');
      $accountPassword2 = (isset($_POST['account_password2']) === true ? md5($_POST['account_password2']) : '');
      $name = (isset($_POST['name']) === true ? $_POST['name'] : '');
      $email = (isset($_POST['email']) === true ? $_POST['email'] : '');

      if (!$accountId || !$accountPassword1 || !$accountPassword2 || !$name || !$email) {
          commonMoveAlert('회원가입 항목을 모두 입력해주세요.', MEMBER_DIR.'/login.php');
      }

      if ($accountId) {
          // 아이디 검사
          $result = validMemberId($accountId);
          if ($result == false) {
              commonMoveAlert('영문만 입력 가능합니다.', MEMBER_DIR.'/login.php');
          }

          // 금지 아이디 검사
          $result = invalidMemberId($accountId);
          if ($result == true) {
              commonMoveAlert('사용 불가한 아이디입니다.', MEMBER_DIR.'/login.php');
          }

          // 계정 중복 검사
          $params = ['where' => "account_id = '$accountId'"];
          $result = getMember($params);
          if (empty($result) === false) {
              commonMoveAlert('이미 가입된 아이디입니다.', MEMBER_DIR.'/login.php');
          }
      }
      // 비밀번호 검사
      if ($accountPassword1 && $accountPassword2) {
          // 비밀번호 일치 검증
          if ($accountPassword1 !== $accountPassword2) {
              commonMoveAlert('비밀번호를 재확인 해주세요.', MEMBER_DIR.'/login.php');
          }
      }
      // 이메일 유효 검증
      if ($email) {
          $emailRegResult = validEmail($email);
          if ($emailRegResult == false) {
              commonMoveAlert('올바른 이메일 형식으로 입력해주세요.', MEMBER_DIR.'/login.php');
          }
          // 이메일 중복 검사
          $params = ['where' => 'email = "'.$email.'"'];
          $result = getMember($params);
          if ($result) {
              commonMoveAlert('가입된 이메일입니다.', MEMBER_DIR.'/login.php');
          }
      }
      // 이름 검사
      if ($name) {
          // Do Someting
      }

      // 회원 추가
      $params = ['account_id' => $accountId, 'account_password' => $accountPassword1, 'name' => $name, 'email' => $email];
      $lastInsertId = setMember($params);

      // 세션 추가 및 페이지 이동(게시판 목록)
      $params['id'] = $lastInsertId;
      setMemberSession($params);
      $msg = '회원가입을 환영합니다.';
      $location = BOARD_DIR.'/list.php';
      commonMoveAlert($msg, $location);
  } else {
      commonMoveAlert('올바르지 않는 요청입니다.', MEMBER_DIR.'/login.php');
  }
