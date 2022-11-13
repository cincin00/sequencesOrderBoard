<?php
    require_once('../../../index.php');
    // 게시판 설정 로드
    $boardQuery = "SELECT * FROM board WHERE id='1'";
    $boardResult = $dbh->query($boardQuery);
    $boardData = $boardResult->fetch();

    // 게시판 카테고리 로드
    $categoryQuery = "SELECT * FROM board_category ORDER BY sort_order";
    $categoryResult = $dbh->query($categoryQuery);
    foreach ($categoryResult as $categoryData) {
        $category[$categoryData['sort_order']] = [
            'category_id' => $categoryData['id'],
            'title' => $categoryData['title']
        ];
    }

    // 게시글 정보 조회
    $postBaseQuery = "SELECT `po`.id, `po`.title , `po`.writer, `po`.contents, `po`.regist_date, `po`.hits, `bc`.id as `category_id`, `bc`.title as `category_title` FROM post as `po`";
    $postJoinQuery = " LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id";
    $postWhereQuery = " WHERE `po`.id = ".$_GET['id'];
    $postQuery = $postBaseQuery.$postJoinQuery.$postWhereQuery;
    $postResult = $dbh->query($postQuery);
    $postData = $postResult->fetch();
    if(empty($postData)){
        echo '<script>alert(`존재하지 않는 게시글입니다.`); location.href = "'.BOARD_DIR.'/list.php";</script>';
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>개인 프로젝트 사이트</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <link href="<?=DOMAIN?>/public/css/board.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">
      <header class="py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
          <div class="col-4 text-center">
            <a class="blog-header-logo text-dark" href="<?=BOARD_DIR?>/list.php"><h1><?=$boardData['title'] ?></h1></a>
          </div>
        </div>
      </header>
      <body>
        <form name="post_modify" action="<?=BOARD_DIR?>/modify_process.php" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return validForm();">
            <input type="hidden" name="board_id" value="<?=$boardData['id']?>">
            <div class="form-layer">
                <div class="post-layer">
                    <div class="post-title-layer">
                        <div class="title-layer row" style="display:flex">
                            <div class="col-md-4">
                                <select name="board_category" class="form-control">
                                    <option>카테고리 선택</option>
                                    <?php foreach ($category as $index => $categoryData) { ?>
                                        <option value='<?=$categoryData['category_id']?>' <?php if($postData['category_id']===$categoryData['category_id']){ echo "selected='true'";} ?> ><?=$categoryData['title']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" id="post_title" placeholder="제목을 입력해주세요." maxlength="255" class="form-control" value="<?=$postData['title']?>">
                            </div>                        
                        </div>
                        </div>
                    </div>
                    <div class="post-content-layer">
                        <div class="form-group">
                            <textarea name="contents" id="post_content"><?=$postData['contents']?></textarea>
                        </div>
                        <div class="form-group form-inline">
                            <label for="post_writer">작성자</label>
                            <span id="post_writer" style="width:50%"><?=$postData['writer']?></span>
                        </div>
                        <div class="form-group form-inline">
                            <label for="post_password">비밀번호</label>
                            <input type="password" name="password" id="post_password" class="form-control" placeholder="비밀번호를 입력해주세요." maxlength="255" style="width:50%">
                        </div>
                    </div>
                    <div class="post-content-layer row">
                        <div class="col-md-12 text-center">
                            <button type="reset" id="cancel" class="btn btn-default">취소</button>
                            <button type="submit" class="btn btn-primary">수정</button>
                        </div>     
                    </div>
                </div>
            </div>
        </form>
      </body>
    </div>
    <!-- Javascript File-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?=DOMAIN?>/public/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="<?=DOMAIN?>/public/vender/popper.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/holder.min.js"></script>
    <script type="text/javascript" src="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/js/froala_editor.pkgd.min.js"></script>
    <script type="text/javascript" src="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/js/languages/ko.js"></script>
    <script type="text/javascript" src="<?=DOMAIN?>/public/js/board/modify.js?ver=<?=date('YmdHis')?>"></script>
    <script>
      /** Holder JS */
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
      /** Froala Editor JS */
      let editor = new FroalaEditor('#post_content',{
        // 플로라 에디터 국문 언어팩 옵션
        language: 'ko',
      });
      /** Modify JS */
      let modify = new Modify();
      modify.cancelClick('<?=BOARD_DIR?>');

      function validForm(){
        let result = modify.validForm();
        return result;
      }
    </script>
    <!-- CSS File -->
    <link href="<?=DOMAIN?>/public/vender/froala_editor_4.0.15/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
  </body>
</html>