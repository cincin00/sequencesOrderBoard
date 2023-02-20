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
        //session_destroy();
        //session_start();
    }
}

/**
 * 로그인 회원 세션 조회
 *
 * @param array $params 조회할 세션 키
 * @return array
 */
function getMemberSession(array $params = [])
{
    $response = [];

    if (empty($params)) {
        $response = $_SESSION;
    } else {
        if (in_array('id', $params)) {
            $response['id'] = $_SESSION['id'];
        }
        if (in_array('account_id', $params)) {
            $response['account_id'] = $_SESSION['account_id'];
        }
        if (in_array('account_password', $params)) {
            $response['account_password'] = $_SESSION['account_password'];
        }
        if (in_array('name', $params)) {
            $response['name'] = $_SESSION['name'];
        }
        if (in_array('email', $params)) {
            $response['email'] = $_SESSION['email'];
        }
    }

    return $response;
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
    if (validSingleData($params, 'mypageToken')) {
        $_SESSION['mypageToken'] = $params['mypageToken'];
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

function updateMember(array $params)
{
    global $dbh;
    $response = false;

    $table = "";

    // 수정 처리 시 변경 값은 필수값
    if (validSingleData($params, 'set')) {
        $query = 'UPDATE member SET '.$params['set'];
    } else {
        commonAlert('회원 정보 수정 실패.');
    }

    if (validSingleData($params, 'where')) {
        $query .= ' WHERE '.$params['where'];
    }

    if (validSingleData($params, 'debug')) {
        if ($params['debug'] === true) {
            dd($query);
        }
    }

    $response = $dbh->exec($query);

    return $response;
}
