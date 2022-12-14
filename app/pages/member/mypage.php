<?php
    require_once('../../../index.php');

     // 회원 세션 검증
    $session = getMemberSession();
    if(validSingleData($session, 'id')){        
        if($_POST){
            // 회원 정보 검증
            $params = ['where'=>' id = "'.$session['id'].'" AND account_password = "'.md5($_POST['mypage_pw']).'"'];
            $memberData = getMember($params);
            if($memberData){
                // 마이페이지 세션 추가
                $_SESSION['mypageToken'] = 'Y';
                header( 'Location: '.MEMBER_DIR.'/mypage.php');
            }else{
                commonMoveAlert('비밀번호가 틀렸습니다.',BOARD_DIR.'/list.php');
            }
        }
    }else{
        commonMoveAlert('로그인이 필요한 서비스입니다.', BOARD_DIR.'/list.php');
    }
?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <div class="container">
        <!-- 게시판 목록 헤더 -->
        <div class="blog-header py-3">
            <?php include_once('../layout/layout_header.php');?>
        </div>
        <?php if(validSingleData($session, 'mypageToken')){ ?>
        <form name="mypage_info" action="<?=MEMBER_DIR?>/mypage_process.php" method="post" autocomplete="off"
            onsubmit="return mypage.validMypageInfoForm();">
            <table>
                <tr>
                    <td>아이디</td>
                    <td><?=$session['account_id'];?></td>
                </tr>
                <tr>
                    <td>현재 비밀번호</td>
                    <td><input type="password" name="mypage_current_pw" id="mypage_current_pw"></td>
                </tr>
                <tr>
                    <td>변경할 비밀번호</td>
                    <td><input type="password" name="mypage_pw1" id="mypage_pw1"></td>
                </tr>
                <tr>
                    <td>변경할 비밀번호 재확인</td>
                    <td><input type="password" name="mypage_pw2" id="mypage_pw2"></td>
                </tr>
                <tr>
                    <td>이름</td>
                    <td><input type="text" name="mypage_name" id="mypage_name" value="<?=$session['name'];?>"></td>
                </tr>
                <tr>
                    <td>이메일</td>
                    <td><input type="text" name="mypage_email" id="mypage_email" value="<?=$session['email'];?>"></td>
                </tr>
                <tr>
                    <td>회원등급</td>
                    <td> 등급 없음 (현재 기능 미제공)</td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit">확인</button></td>
                </tr>
            </table>
        </form>
        <?php } else { ?>
        <form name="mypage_pw_check" action="<?=MEMBER_DIR?>/mypage.php" method="post" autocomplete="off"
            onsubmit="return mypage.validMypagePwForm();">
            <table>
                <tr>
                    <td>비밀번호</td>
                    <td><input type="password" name="mypage_pw" id="mypage_pw"></td>
                </tr>
                <tr>
                    <td colspan="2"><button type="submit">확인</button></td>
                </tr>
            </table>
        </form>
        <?php } ?>
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <!-- Javascript File-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>
        <script src="<?=DOMAIN?>/public/js/member/mypage.js?ver=<?=date('YmdHis')?>"></script>
        <script>
        let mypage = new Mypage();
        </script>
</body>

</html>