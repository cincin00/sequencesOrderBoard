<?php

// 필요한 라이브러리와 설정 파일 로드
require_once 'vendor/autoload.php';
require_once(__DIR__.'/app/libraries/common_lib.php');
require_once(__DIR__.'/app/config/database.php');
// PDO 객체 로드
global $dbh;

// Faker\Factory 클래스의 create() 메소드를 호출하여 Faker\Generator 객체를 생성 - 생성 옵션은 한국어
$faker = Faker\Factory::create('ko_KR');
$groupIdSelectQuery = 'SELECT group_id FROM `post` ORDER BY group_id DESC LIMIT 0,1';
$result = $dbh->query($groupIdSelectQuery)->fetch();
$group_id = $result['group_id'];
// 게시글 데이터를 생성하여 post 테이블에 INSERT 쿼리를 실행.
for ($i = 1; $i <= 1000000; $i++) {
    // 제목
    $title = preg_replace('/[\`\'\"\!\?\,\+\-\_\=\(\)\!\@\#\$\%\^\&\*]/smi', '', $faker->realText(50));
    // 내용
    //$contents = preg_replace('/[\`\'\"]/smi', '', $faker->sentence());
    $contents = preg_replace('/[\`\'\"\!\?\,\+\-\_\=\(\)\!\@\#\$\%\^\&\*]/smi', '', $faker->realText(500));
    // 작성자
    $writer = preg_replace('/[\`\'\"]/smi', '', $faker->name()); 
    // 등록일
    $regist_date = $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'); 
    // 수정일
    $modify_date = $faker->dateTimeBetween($regist_date, 'now')->format('Y-m-d H:i:s'); 
    // 비밀번호
    //$password = $faker->password(); 
    $password = md5('1234'); 
    // 게시판 번호(게시판번호 고정)
    //$board_id = $faker->numberBetween(1, 10);
    $board_id = 1; 
     // 회원 번호(비회원 고정)
    //$member_id = $faker->numberBetween(1, 100);
    $member_id = null;
    // 게시글 카테고리
    $board_category = $faker->numberBetween(1, 5); 
    // 삭제 여부(삭제하지 않도록 고정)
    //$is_delete = $faker->numberBetween(0, 1); 
    $is_delete = 0; 
    // 조회수
    $hits = $faker->numberBetween(0, 1000); 
    // 그룹 번호
    $group_id = $group_id+1;
    // 그룹 순서
    $group_order = 0; 
    // 그룹 깊이
    $group_depth = 0; 

    // $query = "INSERT INTO post (id, board_id, member_id, password, title, contents, board_category, writer, regist_date, modify_date, is_delete, hits, group_id, group_order, group_depth)
    // VALUES ('$i', '$board_id', '$member_id', '$password', '$title', '$contents', '$board_category', '$writer', '$regist_date', '$modify_date', '$is_delete', '$hits', '$group_id', '$group_order', '$group_depth')";
    $query = "INSERT INTO post (board_id, password, title, contents, board_category, writer, regist_date, modify_date, is_delete, hits, group_id, group_order, group_depth)
    VALUES ('$board_id', '$password', '$title', '$contents', '$board_category', '$writer', '$regist_date', '$modify_date', '$is_delete', '$hits', '$group_id', '$group_order', '$group_depth')";
    //dd($query);
    $result = $dbh->exec($query);

    if($result === false){
        dd('쿼리 실행중 오류 발생:'. $sql);
    } else {
        var_dump($query);
    }
}
?>