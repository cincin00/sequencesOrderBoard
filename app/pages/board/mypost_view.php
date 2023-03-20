<?php
    require_once('../../../index.php');
    checkLogin();
    list($postData, $isMember, $isOwn, $isMemberOwn) = mypostViewData($_GET);
    ?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <div class="container">
        <?php include_once('../layout/layout_header.php');?>
        <header class="py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR?>/mypost_list.php">
                        <h1>나의 작성 게시글</h1>
                    </a>
                </div>
            </div>
        </header>

        <body>
            <div class="form-layer">
                <div class="post-layer">
                    <div class="post-title-layer">
                        <div class="title-layer">
                            <h1><?=$postData['title']?></h1>
                        </div>
                        <div class="sub-info-layer">
                            <span style="border-right:solid;padding-right:5px;"><?=$postData['writer']?></span>
                            <span><?=$postData['regist_date']?></span>
                        </div>
                        <div class="sub-tool-layer text-right">
                            <span>
                                <a id="btn_post_reply" href="#" role="button" data-toggle="" data-target="">답글</a>
                            </span>
                            <?php if ($isMember) { ?>
                            <?php if ($isOwn) { ?>
                            <span class="pad-left-small line-left">
                                <a id="btn_post_member_mod" href="#" role="button" title="회원용 수정">수정</a>
                            </span>
                            <span class="pad-left-small line-left">
                                <a id="btn_post_member_del" href="#" role="button">삭제</a>
                            </span>
                            <?php } ?>
                            <?php
                            } else {
                                if ($isMemberOwn === false) {
                                    ?>
                            <span class="pad-left-small line-left">
                                <a id="btn_post_mod" href="#" role="button" data-toggle="modal" data-target="#myModal"
                                    title="비회원용 수정">수정</a>
                            </span>
                            <span class="pad-left-small line-left">
                                <a id="btn_post_del" href="#" role="button" data-toggle="modal"
                                    data-target="#myModal">삭제</a>
                            </span>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="post-content-layer">
                        <?=htmlspecialchars_decode($postData['contents'])?>
                    </div>
                </div>
                <div class="text-center">
                    <a class="btn btn-default" href="<?=BOARD_DIR?>/mypost_list.php" role="button">목록</a>
                </div>
            </div>
        </body>
    </div>
    <?php include_once('./password.php'); ?>
    <!-- Javascript File-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="<?=DOMAIN?>/public/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="<?=DOMAIN?>/public/vender/popper.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/holder.min.js"></script>
    <script type="text/javascript" src="<?=DOMAIN?>/public/js/board/view.js?ver=<?=date('YmdHis')?>"></script>
    <script>
    /** Holder JS */
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
    /** Board View Js */
    // TODO FIXME
    // let viewData = {
    //     baseUrl: '<?=BOARD_DIR?>',
    //     boardId: <?=$boardId?>,
    //     postId: <?=$postData['id']?>,
    // }
    // let view = new View(viewData);
    // $('#passwdSubmitBtn').on('click', function() {
    //     let mode = $(this).data('mode');
    //     let param = view.passwrodFormParamCreate(mode, <?=$postData['id']?>);
    //     // 비밀번호 팝업 이벤트
    //     view.passwordFormEvent(param);
    // });

    $("#password_form #password").on('keydown', function(e) {
        if (e.keyCode === 13) {
            $('#passwdSubmitBtn').trigger('click');
        }
    });

    <?php if ($isMember) { //TODO 수정 필요?>
    $('#btn_post_member_mod').on('click', function() {
        let parmas = view.passwrodFormParamCreate('update', <?=$postData['id']?>)
        view.passwordFormEvent(parmas);
    });
    $('#btn_post_member_del').on('click', function() {
        let response = confirm('게시글을 삭제하시겠습니까?');
        if(response === true){
            let parmas = view.passwrodFormParamCreate('delete', <?=$postData['id']?>)
            view.passwordFormEvent(parmas);
        }
    });
    <?php } ?>
    </script>
</body>

</html>