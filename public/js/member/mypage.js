class Mypage{
    /**
     * Mypage Class 생성자
     */
    constructor(){
    }

    /**
     * mypage 비밀번호 입력 유효성 검증
     */
    validMypagePwForm()
    {
        let pw = $("#mypage_pw").val();
        if(!pw){
            alert('비밀번호 입력은 필수 입니다.');
            return false;
        }else{
            return true;
        }
    }

    /**
     * mypage 회원 정보 변경 유효성 검증
     */
    validMypageInfoForm()
    {
        let currentPw = $("#mypage_current_pw").val();
        let pw1 = $("#mypage_pw1").val();
        let pw2 = $("#mypage_pw2").val();
        let name = $("#mypage_name").val();
        let email = $("#mypage_email").val();

        if(!currentPw){
            alert('현재 비밀번호를 입력해주세요.');
            return false;
        }
        if(!pw1 && pw2){
            alert('변경할 비밀번호를 입력해주세요.');
            return false;
        }
        if(!pw2 && pw1){
            alert('변경 비밀번호 재확인 항목을 입력해주세요.');
            return false;
        }
        if(pw2 !== pw1){
            alert('변경할 비밀번호가 일치하지 않습니다.');
            return false;
        }
        if(!name){
            alert('이름을 입력해주세요.');
            return false;
        }
        if(!email){
            alert('이메일을 입력해주세요.');
            return false;
        }

        return true;
    }
}