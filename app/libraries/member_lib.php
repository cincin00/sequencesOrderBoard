<?php
/**
 * 회원 기능 라이브러리
 */
require_once(BASEPATH.'/app/libraries/common_lib.php');

/**
 * 회원 정보를 조회
 *
 * @param array $params 회원 조회 조건
 * @param int $fetchType 데이터 반환 수(0: 단일, 1: 전체)
 * @return array
 */
function getMember(array $params, int $fetchType = 0)
{
    global $dbh;
    $response = [];

    $table = "member";
    $query = queryBuilder($table, $params);
    $result = $dbh->query($query);
    if ($fetchType > 0) {
        $response = $result->fetchAll();
    } else {
        $response = $result->fetch();
    }

    return $response;
}

/**
 * 세션 검증
 *
 * @return void
 */
function validSession()
{
    if (isset($_SESSION) === false) {
        session_start();
    } else {
        session_destroy();
        session_start();
    }
}

/**
 * 회원 세션 정보 저장
 *
 * @param array $params 회원 정보
 * @return void
 */
function setMemberSession($params)
{
    validSession();
    if (validSingleData($params, 'id')) {
        $_SESSION['id'] = $params['id'];
    }
    if (validSingleData($params, 'account_id')) {
        $_SESSION['account_id'] = $params['account_id'];
    }
    if (validSingleData($params, 'account_password')) {
        $_SESSION['account_password'] = $params['account_password'];
    }
    if (validSingleData($params, 'name')) {
        $_SESSION['name'] = $params['name'];
    }
    if (validSingleData($params, 'email')) {
        $_SESSION['email'] = $params['email'];
    }
}

/**
 * 유효한 회원 아이디 검증
 *
 * @param string $accountId 회원아이디
 * @return bool
 */
function validMemberId(string $accountId)
{
    // 검증 규칙
    $pattern = '/^[a-zA-Z0-9]*$/smi';
    // 검증 아이디
    $subject = $accountId;
    return preg_match($pattern, $subject);
}

/**
 * 유효하지 않은 회원 아이디 검증
 *
 * @param string $accountId 회원아이디
 * @return bool
 */
function invalidMemberId(string $accountId)
{
    // 검증 규칙
    $pattern = '/^[admin|root|manager]*$/smi';
    // 회원 아이디
    $subject = $accountId;
    return preg_match_all($pattern, $subject, $matches);
}

/**
 * 유효한 이메일 양식 검증
 */
function validEmail(string $email)
{
    // 이메일 형식 검증
    $pattern = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i";
    $subject = $email;
    return preg_match($pattern, $subject);
}

/**
 * 회원 데이터 추가
 *
 * @param array $params 회원 데이터
 * @return int
 */
function setMember(array $params)
{
    global $dbh;
    $response = 0;
    $sth = $dbh->prepare("INSERT INTO member (account_id, account_password, name, email) VALUES (?, ?, ?, ?)");
    $sth->execute(array($params['account_id'], $params['account_password'], $params['name'], $params['email']));
    $response = $dbh->lastInsertId();

    return $response;
}
