/**
 * view.php JavaScript
 */
class View {
    /**
     * Class View 생성자
     */
    constructor() {
    }

    /**
     * 게시글 삭제
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
            console.log(res);
            console.log(res.result);
            console.log(res.msg);
            console.log(res.href);
        });
    }
}