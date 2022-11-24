class Login {

    /**
     * Class Login 생성자
     */
    constructor() {
        this.addEventListener();
    }

    /**
     * 이벤트 리스너 핸들러
     */
    addEventListener() {
        this.signinEvent();
        this.signupEvent();
    }

    /**
     * 회원가입 이벤트
     */
    signupEvent() {
        $('#sign-up').on('click', function () {
            console.log('signupEvent');
            $('#login-in').removeClass('block');
            $('#login-up').removeClass('none');

            $('#login-in').addClass('none');
            $('#login-up').addClass('block');
        });
    }

    /**
     * 로그인 이벤트
     */
    signinEvent() {
        $('#sign-in').on('click', function () {
            console.log('signinEvent');
            $('#login-in').removeClass('none');
            $('#login-up').removeClass('block');

            $('#login-in').addClass('block');
            $('#login-up').addClass('none');
        });
    }
}
