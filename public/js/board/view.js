/**
 * view.php JavaScript
 */
class View {
    baseUrl = '';

    /**
     * Class View 생성자
     * 
     * @param {string} baseUrl Ajax Base Url
     */
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    /**
     * 게시글 수정 페이지 이동 이벤트
     */
    modifyPost(formParam) {
        $('#password_form').attr('action', this.baseUrl + '/password_process.php');
        $('#password_form').attr('method', 'post');
        $('#mode').val(formParam.mode);
        $('#password_form').submit();
    }

    /**
     * 게시글 삭제 이벤트
     */
    delete_post(formParam){
        $('#password_form').attr('action', this.baseUrl + formParam.url);
        $('#password_form').attr('method', 'post');
        $('#mode').val(formParam.mode);
        $('#password_form').submit();
    }
}