<?php
    require_once('../../../index.php');
    list($boardId, $boardData, $category, $replyPostData, $isLogin) = boardWriteViewData($_GET);
    ?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <div class="container">
        <?php include_once('../layout/layout_header.php');?>
        <div class="py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR?>/list.php">
                        <h1><?=$boardData['title']?></h1>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <form name="post_write" action="<?=BOARD_DIR?>/write_process.php" method="post"
                enctype="multipart/form-data" autocomplete="off" onsubmit="return validForm();">
                <input type="hidden" name="board_id" value="<?=$boardData['id']?>">
                <?php if (@$replyPostData) { //답변 기능 활성화?>
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
                                            <?php if (@$replyPostData['board_category'] === $categoryData['category_id']) { ?>
                                            selected="selected" <?php } ?>><?=$categoryData['title']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="title" id="post_title" placeholder="제목을 입력해주세요."
                                        maxlength="255" class="form-control" value="<?php if (@$replyPostData['title']) {
                                            echo 'RE: '.@$replyPostData['title'];
                                        } ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post-content-layer">
                        <div class="form-group">
                            <textarea name="contents" id="post_content"><?=@$replyPostData['contents']?></textarea>
                        </div>
                        <div class="form-group form-inline">
                            <?php if ($isLogin === false) { ?>
                            <label for="post_writer">작성자</label>
                            <input type="text" name="writer" id="post_writer" class="form-control"
                                placeholder="작성자를 입력해주세요." maxlength="255" style="width:50%">
                            <?php } else { ?>
                            <label class="control-label">작성자</label>
                            <p class="form-control-static"><?=@$_SESSION['account_id']?></p>
                            <input type="hidden" name="member_id" id="post_member" value="<?=@$_SESSION['id']?>">
                            <?php } ?>
                        </div>
                        <?php if ($isLogin === false) { ?>
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
        </div>
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
        <script type="text/javascript"
            src="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/js/froala_editor.pkgd.min.js">
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