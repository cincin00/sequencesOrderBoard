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
        this.checkSignupEvent();
    }

    /**
     * 회원가입 이벤트
     */
    changeSignupFromEvent() {
        $('#sign-up').on('click', function () {
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
            $('#form-login-up').submit();
        });
    }

    /**
     * 폼 유효성 검증
     * 
     * @param {*} type 
     */
    validForm(type) {
        switch (type) {
            case 'in':
                return this.validLoginInForm();
            case 'up':
                return this.validLoginUpForm();
            default:
                alert('폼 유효성 검증에 실패하였습니다.');
                return false;
        }
    }

    /**
     * 로그인 폼 유효성 검증
     */
    validLoginInForm() {
        let msg = '';
        let target = '';
        let id = $('#form-login-in').find(':input[name="account_id"]');
        let pw = $('#form-login-in').find(':input[name="account_password1"]');
        let result = false;

        if (id.val() === '') {
            msg = '아이디는 필수 입력입니다.';
            target = id;
            this.alertFocus(target, msg);
            return result;
        }

        if (pw.val() === '') {
            msg = '비밀번호는 필수 입력입니다.';
            target = pw;
            this.alertFocus(target, msg);
            return result;
        }

        return true;
    }

    /**
     * 회원가입 폼 유효성 검증
     */
    validLoginUpForm() {
        let msg = '';
        let target = '';
        let id = $('#form-login-up').find(':input[name="account_id"]');
        let pw1 = $('#form-login-up').find(':input[name="account_password1"]');
        let pw2 = $('#form-login-up').find(':input[name="account_password2"]');
        let name = $('#form-login-up').find(':input[name="name"]');
        let email = $('#form-login-up').find(':input[name="email"]');
        let result = false;

        if (id.val() === '') {
            msg = '아이디는 필수 입력입니다.';
            target = id;
            this.alertFocus(target, msg);
            return result;
        }

        if (pw1.val() === '') {
            msg = '비밀번호는 필수 입력입니다.';
            target = pw1;
            this.alertFocus(target, msg);
            return result;
        }

        if (pw2.val() === '') {
            msg = '비밀번호 확인은 필수 입력입니다.';
            target = pw2;
            this.alertFocus(target, msg);
            return result;
        }

        if (name.val() === '') {
            msg = '이름은 필수 입력입니다.'
            target = name;
            this.alertFocus(target, msg);
            return result;
        }

        if (email.val() === '') {
            msg = '이메일은 필수 입력입니다.';
            target = email;
            this.alertFocus(target, msg);
            return result;
        }

        return true;
    };

    /**
     * 회원가입 페이지 전환
     */
    checkSignupEvent(){
        let qstr = document.location.search;
        let isSignup = qstr.indexOf('signup=1');
        if(isSignup === 1){
            $('#sign-up').trigger('click');
        }
    }

    /**
     * 알림을 표시하고 선택자 포커스 처리
     */
    alertFocus(target, msg)
    {
        alert(msg);
        target.focus();
    }
}
