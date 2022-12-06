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
        this.changeSigninFormEvent();
        this.changeSignupFromEvent();
        this.submitSigninFormEvent();
        this.submitSignupFormEvent();
    }

    /**
     * 회원가입 이벤트
     */
    changeSignupFromEvent() {
        $('#sign-up').on('click', function () {
            console.log('signupEvent');
            $('#form-login-in').removeClass('block');
            $('#form-login-up').removeClass('none');

            $('#form-login-in').addClass('none');
            $('#form-login-up').addClass('block');
        });
    }

    /**
     * 로그인 이벤트
     */
    changeSigninFormEvent() {
        $('#sign-in').on('click', function () {
            console.log('signinEvent');
            $('#form-login-in').removeClass('none');
            $('#form-login-up').removeClass('block');

            $('#form-login-in').addClass('block');
            $('#form-login-up').addClass('none');
        });
    }

    submitSigninFormEvent() {
        $("#sign-in-submit-btn").on('click', function () {
            $('#form-login-in').submit();
        });
    }

    submitSignupFormEvent() {
        $("#sign-up-submit-btn").on('click', function () {
            console.log('회원가입 이벤트 클릭');
            $('#form-login-up').submit();
        });
    }

    /**
     * 폼 유효성 검증
     * 
     * @param {*} type 
     */
    validForm(type) {
        let result = false;
        switch (type) {
            case 'in':
                return result = this.validLoginInForm();
            case 'up':
                return result = this.validLoginUpForm();
            default:
                alert('폼 유효성 검증에 실패하였습니다.');
                return result = false;
        }
    }

    /**
     * 로그인 폼 유효성 검증
     */
    validLoginInForm() {
        let msg = '';
        let target = '';
        let id = $('#form-login-in').find(':input[name="account_id"]');
        let pw = $('#form-login-in').find(':input[name="account_password"]');
        let result = false;

        if (id.val() === '') {
            msg = '아이디는 필수 입력입니다.';
            target = id;
        } else if (pw.val() === '') {
            msg = '비밀번호는 필수 입력입니다.';
            target = pw;
        } else {
            result = true;
        }

        if (result === false) {
            alert(msg);
            target.focus();
        }

        return result;
    }

    /**
     * 회원가입 폼 유효성 검증
     */
    validLoginUpForm() {
        let msg = '';
        let target = '';
        let id = $('#form-login-up').find(':input[name="account_id"]');
        let pw = $('#form-login-up').find(':input[name="account_password"]');
        let name = $('#form-login-up').find(':input[name="name"]');
        let email = $('#form-login-up').find(':input[name="email"]');
        let result = false;

        if (id.val() === '') {
            msg = '아이디는 필수 입력입니다.';
            target = id;
        } else if (pw.val() === '') {
            msg = '비밀번호는 필수 입력입니다.';
            target = pw;
        } else if (name.val() === '') {
            msg = '이름은 필수 입력입니다.'
            target = name;
        } else if (email.val() === '') {
            msg = '이메일은 필수 입력입니다.';
            target = email;
        } else {
            result = true;
        }

        if (result === false) {
            alert(msg);
            target.focus();
        }

        return result;
    };
}
