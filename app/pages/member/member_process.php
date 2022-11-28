<?php

  require_once('../../../index.php');
//echo '<pre>';print_r($result);echo '</pre>';
  $mode = (isset($_POST['mode']) === true ? $_POST['mode'] : '');

  if (empty($mode) === true) {
      echo '<script>alert(`올바르지 않는 요청입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
      exit;
  } elseif ($mode === 'signin') {
      $accountId = (isset($_POST['account_id']) === true ? $_POST['account_id'] : '');
      $accountPassword1 = (isset($_POST['account_password1']) === true ? md5($_POST['account_password1']) : '');
      $signinQuery = $dbh->prepare("SELECT * FROM member WHERE account_id = ? AND account_password = ? ");
      $signinQuery->execute([$accountId, $accountPassword1]);
      $result = $signinQuery->fetchAll();
      
      // 게시판 페이지 이동
      $location = MEMBER_DIR.'/login.php';
      if (empty($result) === true) {
          $msg = '회원가입되지 않은 회원입니다.';
      } elseif ($result['withdrawal'] === 1) {
          $msg = '탈퇴한 회원입니다.';
      } else {
          // 세션 저장
          session_start();
          $_SESSION['id'] = $accountId;
          $msg = '로그인되었습니다.';
          $location = BOARD_DIR.'/list.php';
      }
      echo '<script>alert("'.$msg.'");location.href = "'.$location.'"</script>';
      exit;
  } elseif ($mode === 'signup') {
      $accountId = (isset($_POST['account_id']) === true ? $_POST['account_id'] : '');
      $accountPassword1 = (isset($_POST['account_password1']) === true ? md5($_POST['account_password1']) : '');
      $accountPassword2 = (isset($_POST['account_password2']) === true ? md5($_POST['account_password2']) : '');
      $name = (isset($_POST['name']) === true ? $_POST['name'] : '');
      $email = (isset($_POST['email']) === true ? $_POST['email'] : '');

      if(!$accountId || !$accountPassword1 || !$accountPassword2 || !$name || !$email){
        echo '<script>alert(`회원가입 항목을 모두 입력해주세요.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
        exit;
      }
      // 아이디 검사
      if($accountId){
        // 계정 중복 검사
        $signinQuery = $dbh->prepare("SELECT * FROM member WHERE account_id = '$accountId'");
        $signinQuery->execute();
        $result = $signinQuery->fetchAll();

        if(empty($result) === false){
          echo '<script>alert(`이미 가입된 아이디입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
          exit;
        }
      }
      // 비밀번호 검사
      if($accountPassword1 && $accountPassword2){
        // Do Someting
        if($accountPassword1 !== $accountPassword2){
          echo '<script>alert(`비밀번호를 재확인 해주세요.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
          exit;
        }
      }
      // 이메일 검사
      if($email){
        $pattern = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i";
        $emailRegResult = preg_match($pattern, $email);
        if($emailRegResult === false){
          echo '<script>alert(`올바른 이메일 형식으로 입력해주세요.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
          exit;
        }        
      }
      // 이름 검사
      if($name){
        // Do Someting
      }

      // 데이터 추가    
      $sth = $dbh->prepare("INSERT INTO member (account_id, account_password, name, email) VALUES (?, ?, ?, ?)");
      $sth->execute(array($accountId, $accountPassword1, $name, $email));
      
      // 세션 추가 및 페이지 이동(게시판 목록
      session_start();
      $_SESSION['id'] = $accountId;
      $msg = '로그인되었습니다.';
      $location = BOARD_DIR.'/list.php';
  } else {
      echo '<script>alert(`올바르지 않는 요청입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
      exit;
  }