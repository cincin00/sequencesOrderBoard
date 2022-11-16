/**
 * write.php JavaScript
 */
class Write {
    baseUrl = '';

    /**
     * Class Write 생성자
     * 
     * @param {string} baseUrl Ajax Base Url
     */
    constructor(baseUrl) {
        this.baseUrl = baseUrl;

        this.init();
    }

    /**
     * Write Js 생성 시 초기화 이벤트
     */
    init() {
        this.setButtonEventHandler();
    }

    /**
     * 폼 유효성 검증 이벤트
     * 
     * @returns bool
     */
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

        if (msg !== '') {
            alert(msg);
            result = false;
        }

        return result;
    }

    /**
     * 게시글 작성 페이지 버튼 핸들러 바인딩
     */
    setButtonEventHandler() {
        let url = this.baseUrl + '/list.php';
        $("#cancel").on('click', function () { window.location.href = url; });
    }
}