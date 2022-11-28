<?php
    require_once('../../../index.php');

    $baordId = isset($_GET['board_id']) === true ? $_GET['board_id'] : 0;

    // 게시판 설정 로드 - 게층형 게시판 고정
    $boardQuery = "SELECT * FROM board WHERE id=".$baordId;
    $boardResult = $dbh->query($boardQuery);
    $boardData = $boardResult->fetch();
    if(empty($boardData) === true){
      echo '<script>alert(`존재하지 않는 게시판입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
  }

    // 게시판 카테고리 로드
    $categoryQuery = "SELECT * FROM board_category ORDER BY sort_order";
    $categoryResult = $dbh->query($categoryQuery);
    foreach ($categoryResult as $categoryData) {
        $category[$categoryData['sort_order']] = [
            'category_id' => $categoryData['id'],
            'title' => $categoryData['title']
        ];
    }

    // 답글 기능 로드
    if(isset($_GET['reply']) === true && empty($_GET['reply']) === false){
      $replyId = $_GET['reply'];
      if( $replyId > 0 ){
        $replyQuery = "SELECT `po`.*, `bc`.title as `category_title` FROM post as `po` LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id WHERE `po`.id = ".$replyId." AND `po`.board_id = ".$baordId;
        $replyPostResult = $dbh->query($replyQuery);
        $replyPostData = $replyPostResult->fetch();
      }else {
        echo '<script>alert(`존재하지 않는 게시글입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
      }
    }

    // 로그인 검증
    $isLogin = (isset($_SESSION['id']) === true ? true : false);
?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <div class="container">
        <header class="py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR?>/list.php">
                        <h1><?=$boardData['title']?></h1>
                    </a>
                </div>
            </div>
        </header>

        <body>
            <form name="post_write" action="<?=BOARD_DIR?>/write_process.php" method="post"
                enctype="multipart/form-data" autocomplete="off" onsubmit="return validForm();">
                <input type="hidden" name="board_id" value="<?=$boardData['id']?>">
                <?php if(@$replyPostData){ //답변 기능 활성화 ?>
                <input type="hidden" name="group" value="<?=@$replyPostData['group_id']?>">
                <input type="hidden" name="depth" value="<?=@$replyPostData['group_depth']?>">
                <input type="hidden" name="order" value="<?=@$replyPostData['group_order']?>">
                <?php } ?>
                <div class="form-layer">
                    <div class="post-layer">
                        <div class="post-title-layer">
                            <div class="title-layer row" style="display:flex">
                                <div class="col-md-4">
                                    <select name="board_category" class="form-control">
                                        <option value="">카테고리 선택</option>
                                        <?php foreach ($category as $index => $categoryData) { ?>
                                        <option value='<?=$categoryData['category_id']?>'
                                            <?php if(@$replyPostData['board_category'] === $categoryData['category_id']){ ?>
                                            selected="selected" <?php } ?>><?=$categoryData['title']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="title" id="post_title" placeholder="제목을 입력해주세요."
                                        maxlength="255" class="form-control"
                                        value="<?php if(@$replyPostData['title']) { echo 'RE: '.@$replyPostData['title'];} ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post-content-layer">
                        <div class="form-group">
                            <textarea name="contents" id="post_content"><?=@$replyPostData['contents']?></textarea>
                        </div>
                        <div class="form-group form-inline">                            
                            <?php if($isLogin === false){ ?>
                                <label for="post_writer">작성자</label>
                                <input type="text" name="writer" id="post_writer" class="form-control"
                                placeholder="작성자를 입력해주세요." maxlength="255" style="width:50%">
                            <?php } else { ?>
                                <label class="control-label">작성자</label>
                                <p class="form-control-static"><?=@$_SESSION['account_id']?></p>
                                <input type="hidden" name="member_id" id="post_member" value="<?=@$_SESSION['id']?>" >
                            <?php } ?>
                        </div>
                        <?php if($isLogin === false){ ?>
                        <div class="form-group form-inline">
                            <label for="post_password">비밀번호</label>
                            <input type="password" name="password" id="post_password" class="form-control"
                                placeholder="비밀번호를 입력해주세요." maxlength="255" style="width:50%">
                        </div>
                        <?php } ?>
                    </div>
                    <div class="post-content-layer row">
                        <div class="col-md-12 text-center">
                            <button type="reset" id="cancel" class="btn btn-default">취소</button>
                            <button type="submit" class="btn btn-primary">등록</button>
                        </div>
                    </div>
                </div>
            </form>
        </body>
</body>
<!-- Javascript File-->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
    integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script>
window.jQuery || document.write('<script src="<?=DOMAIN?>/public/vendor/jquery-slim.min.js"><\/script>')
</script>
<script src="<?=DOMAIN?>/public/vender/popper.min.js"></script>
<script src="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
<script src="<?=DOMAIN?>/public/vender/holder.min.js"></script>
<script type="text/javascript" src="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/js/froala_editor.pkgd.min.js">
</script>
<script type="text/javascript" src="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/js/languages/ko.js"></script>
<script type="text/javascript" src="<?=DOMAIN?>/public/js/board/write.js?ver=<?=date('YmdHis')?>"></script>
<script>
/** Holder JS */
Holder.addTheme('thumb', {
    bg: '#55595c',
    fg: '#eceeef',
    text: 'Thumbnail'
});
/** Froala Editor JS */
let editor = new FroalaEditor('#post_content', {
    // 플로라 에디터 국문 언어팩 옵션
    language: 'ko',
});
/** Write JS */
let write = new Write('<?=BOARD_DIR?>');

function validForm() {
    return write.validForm();
}
</script>
<!-- CSS File -->
<link href="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/css/froala_editor.pkgd.min.css" rel="stylesheet"
    type="text/css" />
</body>

</html>