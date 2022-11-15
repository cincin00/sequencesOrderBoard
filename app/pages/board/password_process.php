<?php
    require_once('../../../index.php');
    
    // 반환값 초기화
    $response = [
        'msg' => '',
        'href' => '',
    ];
    // 게시글 번호
    $id = $_POST['post_id'];
    // 게시글 처리방식(수정:update,삭제:delete)
    $mode = $_POST['mode'];
    // 게시글 비밀번호(샤용자 입력)
    $password = $_POST['password'];

    //try{
        // 게시글 정보 조회
        $postBaseQuery = "SELECT `po`.id, `po`.title , `po`.writer, `po`.contents,`po`.password, `po`.regist_date, `po`.hits, `bc`.id as `category_id`, `bc`.title as `category_title` FROM post as `po`";
        $postJoinQuery = " LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id";
        $postWhereQuery = " WHERE `po`.id = ".$id;
        $postQuery = $postBaseQuery.$postJoinQuery.$postWhereQuery;
        $postResult = $dbh->query($postQuery);
        $postData = $postResult->fetch();

        // 공통 검증 - 게시글이 없을때, 비밀번호가 틀릴때
        if(empty($postData) === true){
            $response['msg'] = '존재하지 않는 게시글입니다.';
            $response['href'] = BOARD_DIR.'/list.php';
            //throw new Exception($response['msg']);
            echo '<script>alert(`'.$response['msg'].'`); location.href = "'.$response['href'].'";</script>';
            exit;
        }elseif($postData['password'] !== md5($password)){
            $response['msg'] = '비밀번호가 올바르지 않습니다.';
            $response['href'] = BOARD_DIR.'/view.php?id='.$id;
            //throw new Exception($response['msg']);
            echo '<script>alert(`'.$response['msg'].'`); location.href = "'.$response['href'].'";</script>';
            exit;
        }
        if($mode === 'update'){
            include_once(BASEPATH.'/app/pages/board/modify.php');
        }elseif($mode === 'delete'){
            // 
        }
    // }catch(Exception $e){
    //     echo json_encode([
    //         'error' => [
    //             'msg' => $e->getMessage(),
    //             'code' => $e->getCode(),
    //             'href' => $response['href']
    //         ]
    //     ]);
    //     exit;
    // }

    //echo json_encode([]);