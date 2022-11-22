<?php
    //echo 'login.php';
    require_once('../../../index.php');
    require_once('../header.php');
?>
<!doctype html>
<html lang="en">
<div class="login">
    <div class="login__content">
        <div class="login__img">
            <img src="https://image.freepik.com/free-vector/code-typing-concept-illustration_114360-3581.jpg"
                alt="user login">
        </div>
        <div class="login__forms">
            <!-- login form -->
            <form action="" class="login__register" id="login-in">
                <h1 class="login__title">로그인</h1>
                <div class="login__box">
                    <i class='bx bx-user login__icon'></i>
                    <input type="text" placeholder="Username" class="login__input">
                </div>
                <div class="login__box">
                    <i class='bx bx-lock login__icon'></i>
                    <input type="text" placeholder="Password" class="login__input">
                </div>
                <a href="#" class="login__forgot">비밀번호를 찾으시나요? </a>

                <a href="#" class="login__button">로그인</a>

                <div>
                    <span class="login__account login__account--account">계정이 없으신가요?</span>
                    <span class="login__signin login__signin--signup" id="sign-up">회원가입</span>
                </div>
            </form>

            <!-- create account form -->
            <form action="" class="login__create none" id="login-up">
                <h1 class="login__title">계정 생성</h1>
                <div class="login__box">
                    <i class='bx bx-user login__icon'></i>
                    <input type="text" placeholder="Username" class="login__input">
                </div>

                <div class="login__box">
                    <i class='bx bx-at login__icon'></i>
                    <input type="text" placeholder="Email" class="login__input">
                </div>

                <div class="login__box">
                    <i class='bx bx-lock login__icon'></i>
                    <input type="text" placeholder="Password" class="login__input">
                </div>

                <a href="#" class="login__button">회원 가입</a>

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
<script src="<?=DOMAIN?>/public/js/member/login.js?ver=<?=date('YmdHis')?>"></script>
</body>

</html>