<?php

    require_once('../../../index.php');

    // 폼 전송 데이터 수신
    $params['current_pw'] = (isset($_POST['mypage_current_pw']) === true ? $_POST['mypage_current_pw'] : '');
    $params['pw1'] = (isset($_POST['mypage_pw1']) === true ? $_POST['mypage_pw1'] : '');
    $params['pw2'] = (isset($_POST['mypage_pw2']) === true ? $_POST['mypage_pw2'] : '');
    $params['name'] = (isset($_POST['mypage_name']) === true ? $_POST['mypage_name'] : '');
    $params['email'] = (isset($_POST['mypage_email']) === true ? $_POST['mypage_email'] : '');

    // 폼 데이터 유효성 검증
    if (empty($params['current_pw'])) {
        $msg = '현재 비밀번호는 필수입니다.';
    } elseif (!empty($params['pw1']) && empty($params['pw2'])) {
        $msg = '변경할 비밀번호는 필수입니다.';
    } elseif (!empty($params['pw2']) && empty($params['pw1'])) {
        $msg = '변경할 비밀번호 재확인 항목은 필수입니다.';
    } elseif ($params['pw1'] !== $params['pw2']) {
        $msg = '변경할 비밀번호가 일치하지 않습니다.';
    } elseif (empty($params['name'])) {
        $msg = '이름은 필수입니다.';
    } elseif (empty($params['email'])) {
        $msg = '이메일은 필수입니다.';
    } elseif (!validEmail($params['email'])) {
        $msg = '올바른 이메일 형식으로 입력해주세요.';
    } else {
        $msg = '';
    }

    if (empty($msg) === false) {
        commonMoveAlert($msg, MEMBER_DIR.'/mypage.php');
    }

    // 이메일 중복 검증
    $memberSession = getMemberSession();
    // TODO 입력 받은 현재 비밀번호 체크 없음ㄴ
    $memberData = getMember(['where' => ' email = "'.$params['email'].'"']);
    if (!empty($memberData) && $memberData['id'] !== $memberSession['id']) {
        commonMoveAlert('가입된 이메일입니다.', MEMBER_DIR.'/mypage.php');
    }

    if (empty($params['pw1'])) {
        $md5Pw = $memberSession['account_password'];
    } else {
        $md5Pw = md5($params['pw1']);
    }

    $update = [
        'set' => 'account_password = "'.$md5Pw.'", name = "'.$params['name'].'", email = "'.$params['email'].'"',
        'where' => ' id = '.$memberSession['id'],
        //'debug' => true
    ];
    $result = updateMember($update);
    $sessionInfo = [
        'id' => $memberData['id'],
        'account_id' => $memberSession['account_id'],
        'account_password' => $md5Pw,
        'name' => $params['name'],
        'email' => $params['email'],
        'mypageToken' => 'Y'
    ];
    setMemberSession($sessionInfo);
    commonMoveAlert('회원 정보가 변경되었습니다.', MEMBER_DIR.'/mypage.php');
