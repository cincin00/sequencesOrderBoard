/**
 * view.php JavaScript
 */
class View {
    baseUrl = '';
    postId = 0;
    /**
     * Class View 생성자
     * 
     * @param {string} baseUrl Ajax Base Url
     */
    constructor(baseUrl, postId) {
        this.baseUrl = baseUrl;
        this.postId = postId;
        this.init();
    }

    /**
     * View Js 생성 시 초기화 이벤트
     */
    init(){
        this.setButtonEventHandler();
    }
    /**
     * 비밀번호 팝업 이벤트
     * 
     * @param {Object} formParam 폼 데이터
     */
    passwordFormEvent(formParam) {
        $('#password_form').attr('action', this.baseUrl + formParam.url);
        $('#password_form').attr('method', formParam.method);
        $('#mode').val(formParam.mode);
        $('#password_form').submit();
    }

    /**
     * 비밀번호 폼 데이터 구성 이벤트
     * 
     * @param {string} param_mode 
     * @param {int} param_post_id 
     * @returns 
     */
    passwrodFormParamCreate(param_mode, param_post_id) {
        return {mode: param_mode, method: 'post', url: '/password_process.php', id: param_post_id, password: $('#password').val()}
    }

    /**
     * 게시글 상세 페이지 버튼 핸들러 바인딩
     */
    setButtonEventHandler(){
        $("#btn_post_reply").on('click', function(){ console.log('답글 버튼 클릭');});
        $('#btn_post_mod').on('click', function(){ $('#myModal #passwdSubmitBtn').data('mode', 'update');});
        $('#btn_post_del').on('click',function(){$('#myModal #passwdSubmitBtn').data('mode', 'delete');});
    }
}