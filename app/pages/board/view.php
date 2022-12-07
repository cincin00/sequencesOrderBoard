<?php
    require_once('../../../index.php');
    
    $baordId = isset($_GET['board_id']) === true ? $_GET['board_id'] : 0;
    $postId = isset($_GET['id']) === true ? $_GET['id'] : 0;
    // 게시판 설정 로드
    $boardQuery = "SELECT * FROM board WHERE id=".$baordId;
    $boardResult = $dbh->query($boardQuery);
    $boardData = $boardResult->fetch();
    if (empty($boardData) === true) {
        echo '<script>alert(`존재하지 않는 게시판입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
    }

    // 게시글 정보 조회
    $postQuery = "SELECT `po`.*, `bc`.title as `category_title` FROM post as `po` LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id WHERE `po`.id = ".$postId." AND `po`.board_id = ".$baordId;
    $postResult = $dbh->query($postQuery);
    $postData = $postResult->fetch();
    if (empty($postData) === true) {
        echo '<script>alert(`존재하지 않는 게시글입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
    }

    // 조회수 증가 처리
    $hitsUpdateQuery = "UPDATE post SET hits = hits + 1 WHERE id = ".$postId;
    $dbh->query($hitsUpdateQuery);

    // 비회원, 회원 게시글 분기 처리
    $isMember = $isOwn = $isMemberOwn = false;
    if ($postData['member_id']) {
        $isMemberOwn = true;
        if (isset($_SESSION['id'])) {
            $isMember = true;
            if ($postData['member_id'] === $_SESSION['id']) {
                $isOwn = true;
            }
        }
    }
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
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR?>/list.php">
                        <h1><?=$boardData['title'] ?></h1>
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
                <?php if (false) { // 게시판 댓글 비활성화?>
                <div class="comment-layer">
                    <div class="form-group comment-head">
                        댓글(<span class="">0</span>)
                    </div>
                    <div class="comment-body container-fluid">
                        <div class="input-layer row">
                            <div class="form-group col-xs-3">
                                <label class="sr-only" for="comment_writer">작성자</label>
                                <input type="text" name="comment_writer" id="comment_writer" class="form-control"
                                    placeholder="작성자">
                            </div>
                            <div class="form-group col-xs-3">
                                <label class="sr-only" for="comment_password">비밀번호</label>
                                <input type="password" name="comment_pasword" id="comment_pasword" class="form-control"
                                    placeholder="비밀번호">
                            </div>
                        </div>
                        <div class="comment-editor-layer row">
                            <div class="col-xs-12 col-md-8">
                                <textarea class="form-control" row="3" placeholder="댓글 내용을 입력해주세요."></textarea>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <button class="btn btn-primary pad-large">댓글 등록</button>
                            </div>
                        </div>
                    </div>
                    <div class="comment-list container-fluid">
                        <!-- sample dom -->
                        <div class="row">
                            <span class="col-md-2">지나가던 법사</span>
                            <span class="col-md-*">2022-11-08 11:00:00</span>
                        </div>
                        <div class="row">
                            <span class="col-md-10">지금 딜러 엄청 너프됬는데 ㅎㅎㅎ .. 그냥 법사하세요. 여러분~</span>
                        </div>
                        <div class="row">
                            <span class="col-md-1">
                                <a href="<?=BOARD_DIR?>/" role="button">수정</a>
                            </span>
                            <span class="line-right line-left col-md-1">
                                <a href="<?=BOARD_DIR?>/" role="button">삭제</a>
                            </span>
                            <span class="col-md-1">
                                <a href="<?=BOARD_DIR?>/" role="button">답글</a>
                            </span>
                        </div>
                        <!-- sample dom -->
                    </div>
                </div>
                <?php } ?>
                <div class="text-center">
                    <a class="btn btn-default" href="<?=BOARD_DIR?>/list.php" role="button">목록</a>
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
    let viewData = {
        baseUrl: '<?=BOARD_DIR?>',
        boardId: <?=$baordId?>,
        postId: <?=$postData['id']?>,
    }
    let view = new View(viewData);
    $('#passwdSubmitBtn').on('click', function() {
        let mode = $(this).data('mode');
        let param = view.passwrodFormParamCreate(mode, <?=$postData['id']?>);
        // 비밀번호 팝업 이벤트
        view.passwordFormEvent(param);
    });

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