/**
 * write.php JavaScript
 */
class Write {
    /**
     * Class Write 생성자
     */
    constructor() {
        console.log('write.js is load.');
    }

    validForm() {        
        let title = $('#post_title').val();
        let content = $('#post_content').val();
        let writer = $('#post_writer').val();
        let password = $('#post_password').val();
        let msg = '';
        let result = true;

        if (!title) {
            msg = '게시글 제목은 필수입니다.';
        } else if (!writer) {
            msg = '게시글 작성자는 필수입니다.';
        } else if (!content) {
            msg = '게시글 내용은 필수입니다.';
        } else if (!password) {
            msg = '게시글 비밀번호는은 필수입니다.';
        }

        if(msg !== ''){
            alert(msg);
            result = false;
        }

        return result;
    }

    /**
     * 취소 버튼 이벤트
     * 
     * @param {*} domain 
     */
    cancelClick(domain = '') {
        $("#cancel").on('click', function () {
            window.location.href = domain + '/list.php';
        });
    }
}