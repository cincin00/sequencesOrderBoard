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
<?php if(validSingleData($session, 'mypageToken')){ ?>
    <body>
        <!-- 게시판 목록 헤더 -->
        <div class="blog-header" style="display:flow-root;">
            <?php include_once('../layout/layout_header.php');?>
        </div>
        <form name="mypage_info" action="<?=MEMBER_DIR?>/mypage_process.php" method="post" autocomplete="off"
            onsubmit="return mypage.validMypageInfoForm();">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">마이페이지</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="control-label">아이디</label>
                        <div>
                            <p class="form-control-static"><?=$session['account_id'];?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mypage_current_pw">현재 비밀번호</label>
                        <input type="password" name="mypage_current_pw" class="form-control" id="mypage_current_pw" placeholder="현재 비밀번호">
                    </div>
                    <div class="form-group">
                        <label for="mypage_pw1">변경할 비밀번호</label>
                        <input type="password" name="mypage_pw1" class="form-control" id="mypage_pw1" placeholder="변경할 비밀번호">
                    </div>
                    <div class="form-group">
                        <label for="mypage_pw2">변경할 비밀번호 재확인</label>
                        <input type="password" name="mypage_pw2" class="form-control" id="mypage_pw2" placeholder="변경할 비밀번호 재확인">
                    </div>
                    <div class="form-group">
                        <label for="">이름</label>
                        <input type="text" name="mypage_name" class="form-control" id="mypage_name" placeholder="이름" value="<?=$session['name'];?>">
                    </div>
                    <div class="form-group">
                        <label for="">이메일</label>
                        <input type="text" name="mypage_email" class="form-control" id="mypage_email" placeholder="이메일" value="<?=$session['email'];?>">
                    </div>
                    <div class="form-group">
                        <label for="">회원등급</label>
                        <div>
                            <p class="form-control-static">등급 없음 (현재 기능 미제공)</p>
                        </div>
                    </div>                                        
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">확인</button>
                </div>
            </div>
        </form>
<?php } else { ?>
    <body class="hold-transition login-page">
        <div class="login-box" style="align-items:center;justify-content:center;">
            <div class="login-logo">
                <a href="#"><b>비밀번호 확인</b></a>
            </div>
            <div class="card">
                <form name="mypage_pw_check" action="<?=MEMBER_DIR?>/mypage.php" method="post" autocomplete="off" onsubmit="return mypage.validMypagePwForm();">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">개인정보 보호를 위해서 다시 비밀번호를 입력해주세요.</p>
                        <div class="input-group mb-3">
                            <input type="password" name="mypage_pw" id="mypage_pw" class="form-control" placeholder="비밀번호를 입력해주세요.">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">확인</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>        
<?php } ?>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?=ADMIN_PLUGIN?>/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="<?=ADMIN_PLUGIN?>/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?=ADMIN_DIST?>/css/adminlte.min.css">
        <!-- jQuery -->
        <script src="<?=ADMIN_PLUGIN?>/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?=ADMIN_PLUGIN?>/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?=ADMIN_DIST?>/js/adminlte.min.js"></script>        
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