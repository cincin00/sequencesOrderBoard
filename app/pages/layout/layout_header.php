<?php
  $memberLogin = false;
  if (isset($_SESSION['id']) === true && empty($_SESSION['id']) === false) {
      $memberLogin = true;
  }
?>
<div class="header-sub">
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <?php if (!@$memberLogin) { ?>
        <!-- 비회원 상단 메뉴 -->
        <div class="col-md-2 pos-right">
            <a class="btn btn-link" href="<?=MEMBER_DIR?>/join.php" role="button">회원가입</a>
        </div>
        <div class="col-md-2 pos-right">
            <a class="btn btn-link" href="<?=MEMBER_DIR?>/login.php" role="button">로그인</a>
        </div>
        <?php } elseif (@$memberLogin) { ?>
        <!-- 회원 상단 메뉴 -->
        <?php if(validSingleData($_SESSION, 'id') && CURRENT_PAGE !== 'mypage'){ ?>
        <div class="col-md-2 pos-right">
            <a class="btn btn-link" href="<?=MEMBER_DIR?>/mypage.php" role="button">마이페이지</a>
        </div>
        <?php } ?>
        <?php if(CURRENT_PAGE === 'mypage'){ ?>
        <div class="col-md-2 pos-right">
            <a class="btn btn-link" href="<?=BOARD_DIR?>/mypost.php" role="button">나의 작성 게시글</a>
        </div>
        <div class="col-md-2 pos-right">
            <a class="btn btn-link" href="<?=BOARD_DIR?>/list.php" role="button">게시글 페이지</a>
        </div>
        <?php } ?>
        <div class="col-md-2 pos-right">
            <a class="btn btn-link" href="<?=MEMBER_DIR?>/logout.php" role="button">로그아웃</a>
        </div>
        <div class="col-md-3 pos-right">
            <span class="btn disabled"><?=$_SESSION['account_id']?>님 환영입니다.</span>
        </div>
        <?php } ?>
    </div>
</div>