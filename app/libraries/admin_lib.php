<?php

    /** 관리자 라이브러리 */
    require_once(BASEPATH.'/app/libraries/common_lib.php');
    // 관리자 로그인 여부 확인 처리
    adminSessionCheck();

    /** 관리자 세션 체크 */
    function adminSessionCheck()
    {
        // 접속 페이지
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $needle = $uri[count($uri)-1];
        // 예외 페이지
        $exceptPageList = [
            'login.php',
            'login_process.php'
        ];

        if (in_array($needle, $exceptPageList) === false) {
            if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_name'])) {
                commonMoveAlert('관리자 로그인 정보가 없습니다.', ADMIN_DIR.'/login.php');
            }
        }
    }

    function getAdminData(array $params, int $fetchType = 0)
    {
        global $dbh;
        $response = [];

        $table = "admin_member";
        $query = queryBuilder($table, $params);
        $result = $dbh->query($query);
        if ($fetchType > 0) {
            $response = $result->fetchAll();
        } else {
            $response = $result->fetch();
        }

        return $response;
    }

    function setAdminSession(array $params)
    {
        if (validSingleData($params, 'admin_id')) {
            $_SESSION['admin_id'] = $params['admin_id'];
        }
        if (validSingleData($params, 'admin_id')) {
            $_SESSION['admin_id'] = $params['admin_id'];
        }
        if (validSingleData($params, 'admin_password')) {
            $_SESSION['admin_id'] = $params['admin_id'];
        }
        if (validSingleData($params, 'admin_name')) {
            $_SESSION['admin_name'] = $params['admin_name'];
        }
        if (validSingleData($params, 'admin_email')) {
            $_SESSION['admin_email'] = $params['admin_email'];
        }
    }

    function getMemberForAdminList()
    {
        $params = [
            //'select' => 'member.id, member.account_id, member.name, member.email',
            'where' => '1 = 1'
        ];
        $members = getMember($params, 1);
        $response = $members;

        return $response;
    }

    function getMemberForAdminView()
    {
        $memberId = (isset($_GET['member_id'])===true?$_GET['member_id']:0);
        $params = [
            'where' => 'id='.$memberId,
        ];
        $memberData = getMember($params);
        if(empty($memberData))
        {
            commonMoveAlert('존재하지 않는 회원입니다.', ADMIN_DIR.'/members/list.php');
        }

        return $memberData;
    }

    function getPostForAdminList($boardId)
    {
        $postCondetion = [
            'select' => '`post`.*, `bc`.title as `category_title`',
            'join' => [
                'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
            ],
            'where' => '`post`.board_id = '.$boardId
        ];
        $posts = getPost($postCondetion, 1);

        $response = $posts;

        return $response;
    }

    function getPostForAdminView()
    {
        $postId = (isset($_GET['post_id'])===true?$_GET['post_id']:0);
        $postCondetion = [
            'select' => '`post`.*, `bc`.title as `category_title`',
            'join' => [
                'left' => 'board_category as `bc` ON `post`.board_category = `bc`.id'
            ],
            'where' => '`post`.id = '.$postId
        ];
        $post = getPost($postCondetion);
        $response = $post;
        $response['contents'] = htmlspecialchars_decode($post['contents']);
        $categoryCondtion = [
            ''
        ];
        $response['category'] = getCategoryData();

        return $response;
    }