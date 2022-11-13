class Modify {
    /**
     * Class Modify 생성자
     */
    constructor() {        
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