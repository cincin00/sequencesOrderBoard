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
        // $.post(this.baseUrl + formParam.action, formParam, function(res){
        //     console.log(res);
        // }).fail(function(res){
        //     alert(res.msg);
        // });
        $('#password_form').attr('action', this.baseUrl + '/password_process.php');
        $('#password_form').attr('method', 'post');
        $('#mode').val(formParam.mode);
        $('#password_form').submit();
    }

    /**
     * 게시글 삭제 이벤트
     */
    delete_post(formParam = []){
        // 통신 URL
        let url = formParam['url'];
        // 통신 매개변수 가공
        let id = formParam['id'];
        let password = formParam['password'];
        // 통신 매개변수
        let params = {'id': id,'password': password};

        $.post(url, params, function (result){
            let res = JSON.parse(result);
            if(res){
                alert(res.msg);
                location.href = res.href;
            }
        });
    }
}