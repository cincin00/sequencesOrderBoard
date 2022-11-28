<?php

  require_once('../../../index.php');

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
          $msg = '등록되지않은 회원이거나 회원 정보가 틀렸습니다.';
      } elseif ($result[0]['withdrawal']) {
          $msg = '탈퇴한 회원입니다.';
      } else {
          // 세션 저장
          session_destroy();
          session_start();
          $_SESSION['id'] = $result[0]['id'];
          $_SESSION['account_id'] = $result[0]['account_id'];
          $_SESSION['account_password'] = $result[0]['account_password'];
          $_SESSION['name'] = $result[0]['name'];
          $_SESSION['email'] = $result[0]['email'];
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

      if (!$accountId || !$accountPassword1 || !$accountPassword2 || !$name || !$email) {
          echo '<script>alert(`회원가입 항목을 모두 입력해주세요.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
          exit;
      }
      // 아이디 검사
      if ($accountId) {
          // 허용 문자
          $pattern = '/^[a-zA-Z0-9]*$/smi';
          $subject = $accountId;
          $result = preg_match($pattern, $subject);

          if ($result == false) {
              echo '<script>alert(`영문만 입력 가능합니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
              exit;
          }

          // 금지 아이디 검사
          $pattern = '/^[admin|root|manager]*$/smi';
          $subject = $accountId;
          $result = preg_match_all($pattern, $subject, $matches);
          if ($result == true) {
              echo '<script>alert(`사용 불가한 아이디입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
              exit;
          }

          // 계정 중복 검사
          $signupIdQuery = $dbh->prepare("SELECT * FROM member WHERE account_id = '$accountId'");
          $signupIdQuery->execute();
          $result = $signupIdQuery->fetchAll();

          if (empty($result) === false) {
              echo '<script>alert(`이미 가입된 아이디입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
              exit;
          }
      }
      // 비밀번호 검사
      if ($accountPassword1 && $accountPassword2) {
          // 비밀번호 일치 검증
          if ($accountPassword1 !== $accountPassword2) {
              echo '<script>alert(`비밀번호를 재확인 해주세요.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
              exit;
          }
      }
      // 이메일 유효 검증
      if ($email) {
          // 이메일 형식 검증
          $pattern = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i";
          $emailRegResult = preg_match($pattern, $email);
          if ($emailRegResult == false) {
              echo '<script>alert(`올바른 이메일 형식으로 입력해주세요.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
              exit;
          }
          // 이메일 중복 검사
          $signupEmailQuery = $dbh->prepare("SELECT * FROM member WHERE email = '$email'");
          $signupEmailQuery->execute();
          $result = $signupEmailQuery->fetchAll();
          if($result){
            echo '<script>alert(`가입된 이메일입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
            exit;
          }
      }
      // 이름 검사
      if ($name) {
          // Do Someting
      }

      // 데이터 추가
      $sth = $dbh->prepare("INSERT INTO member (account_id, account_password, name, email) VALUES (?, ?, ?, ?)");
      $sth->execute(array($accountId, $accountPassword1, $name, $email));

      // 세션 추가 및 페이지 이동(게시판 목록)
      session_destroy();
      session_start();
      $_SESSION['id'] = $sth->lastInsertId();
      $_SESSION['account_id'] = $accountId;
      $_SESSION['account_password'] = $accountPassword1;
      $_SESSION['name'] = $name;
      $_SESSION['email'] = $email;
      $msg = '회원가입을 환영합니다.';
      $location = BOARD_DIR.'/list.php';
      echo '<script>alert("'.$msg.'");location.href = "'.$location.'"</script>';
      exit;
  } else {
      echo '<script>alert(`올바르지 않는 요청입니다.`);location.href = "'.MEMBER_DIR.'/login.php";</script>';
      exit;
  }
