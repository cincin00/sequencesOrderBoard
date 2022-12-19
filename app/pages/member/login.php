<?php
    require_once('../../../index.php');
    if(isset($_SESSION['id']) === true){
        header("Location: ".BOARD_DIR."/list.php");
    }
?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <div class="login">
        <div class="login__content">
            <div class="login__img">
                <img src="https://image.freepik.com/free-vector/code-typing-concept-illustration_114360-3581.jpg"
                    alt="user login">
            </div>
            <div class="login__forms">
                <!-- 로그인 폼 -->
                <form method="post" action="<?=MEMBER_DIR?>/member_process.php" class="login__register"
                    id="form-login-in" onsubmit="return validLoginForm('in');">
                    <input type="hidden" name="mode" value="signin">
                    <h1 class="login__title">로그인</h1>
                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="account_id" placeholder="아이디" class="login__input">
                    </div>
                    <div class="login__box">
                        <i class='bx bx-lock login__icon'></i>
                        <input type="password" name="account_password1" placeholder="비밀번호" class="login__input">
                    </div>
                    <a href="#" class="login__forgot">비밀번호를 찾으시나요? </a>

                    <a href="#" id="sign-in-submit-btn" class="login__button" role="button">로그인</a>

                    <div>
                        <span class="login__account login__account--account">계정이 없으신가요?</span>
                        <span class="login__signin login__signin--signup" id="sign-up">회원가입</span>
                    </div>
                </form>

                <!-- 계정 생성 폼 -->
                <form method="post" action="<?=MEMBER_DIR?>/member_process.php" class="login__create none"
                    id="form-login-up" onsubmit="return validLoginForm('up');">
                    <input type="hidden" name="mode" value="signup">
                    <h1 class="login__title">계정 생성</h1>
                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="account_id" placeholder="아이디" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock login__icon'></i>
                        <input type="password" name="account_password1" placeholder="비밀번호" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock login__icon'></i>
                        <input type="password" name="account_password2" placeholder="비밀번호 확인 " class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="name" placeholder="이름" class="login__input">
                    </div>

                    <div class="login__box">
                        <i class='bx bx-at login__icon'></i>
                        <input type="email" name="email" placeholder="이메일" class="login__input">
                    </div>


                    <a href="#" id="sign-up-submit-btn" class="login__button" role="button">회원 가입</a>

                    <div>
                        <span class="login__account login__account--account">계정이 이미 있으신가요?</span>
                        <span class="login__signup login__signup--signup" id="sign-in">로그인</span>
                    </div>

                    <div class="login__social">
                        <a href="#" class="login__social--icon"><i class='bx bxl-facebook'></i></a>
                        <a href="#" class="login__social--icon"><i class='bx bxl-twitter'></i></a>
                        <a href="#" class="login__social--icon"><i class='bx bxl-google'></i></a>
                        <a href="#" class="login__social--icon"><i class='bx bxl-github'></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!-- Javascript File-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="<?=DOMAIN?>/public/js/member/login.js?ver=<?=date('YmdHis')?>"></script>
    <script>
    let login = new Login();

    /**
     * 회원가입, 로그인 폼 유효성 검증
     */
    function validLoginForm(type) {
        return login.validForm(type);
    }
    </script>
</body>

</html>`