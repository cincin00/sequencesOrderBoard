class Modify {
    /**
     * Class Modify 생성자
     */
    constructor() {
    }

    /**
     * 폼 유효성 검증 이벤트
     * 
     * @returns bool
     */
    validForm() {
        let title = $('#post_title').val();
        let content = $('#post_content').val();
        let password = $('#post_password').val();
        let memberId = $('#member_id').val();
        let msg = '';
        let result = true;

        if (!title) {
            msg = '게시글 제목은 필수입니다.';
        } else if (!content) {
            msg = '게시글 내용은 필수입니다.';
        } else if (!password && !memberId) {
            msg = '게시글 비밀번호는은 필수입니다.';
        }

        if (msg !== '') {
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