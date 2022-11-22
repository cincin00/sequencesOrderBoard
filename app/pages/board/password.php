<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form name="password_form" id="password_form">
            <input type="hidden" name="board_id" value="<?=$boardData['id']?>">
            <input type="hidden" name="id" value="<?=$postData['id']?>">
            <input type="hidden" name="mode" id="mode">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">비밀번호를 입력해주세요.</h4>
                </div>
                <div class="modal-body">
                    <input type="password" name="password" id="password" class="form-control" placeholder="비밀번호">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                    <button type="button" class="btn btn-primary" id="passwdSubmitBtn">확인</button>
                </div>
            </div>
        </form>
    </div>
</div>