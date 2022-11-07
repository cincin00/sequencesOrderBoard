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
      <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
          <div class="col-4 text-left">
            <a class="blog-header-logo text-dark" href="<?=BOARD?>/list.php">계층형 게시판 데모</a>
          </div>
        </div>
      </header>
      <body>
        <div class="mar-medium">
          <input type="text" class="form-control pos-right" id="keyword" style="width:300px;" placeholder="검색어를 입력해주세요">
          <button type="button" class="btn btn-default pos-right">검색</button>
        </div>          
        <table class="board-list-table">
          <colgroup>
            <col width="">
            <col width="">
            <col width="">
            <col width="">
            <col width="">
            <col width="">
          </colgroup>
          <thead class="board-list-head">
          <tr>
            <th class="board-list-table-baqh">번호</th>
            <th class="board-list-table-baqh">카테고리</th>
            <th class="board-list-table-baqh">제목</th>
            <th class="board-list-table-baqh">작성자</th>
            <th class="board-list-table-baqh">등록일</th>
            <th class="board-list-table-baqh">조회수</th>
          </tr>
          </thead>
          <tbody class="board-list-head">
            <!-- sample Row -->
            <?php
              if (false) {
            ?>
            <tr>
              <td class="board-list-table-baqh"></td>
              <td class="board-list-table-baqh"></td>
              <td class="board-list-table-baqh"></td>
              <td class="board-list-table-baqh"></td>
              <td class="board-list-table-baqh"></td>
              <td class="board-list-table-baqh"></td>
            </tr>
            <?php
              } else {
            ?>
            <tr>
              <td class="board-list-table-baqh" colspan="6">등록된 게시글이 없습니다.</td>
            </tr>
            <?php
              }
            ?>
            <!-- sample Row -->
          </tbody>
        </table>
        <footer>
          <div class="pos-right">
              <button type="button" class="btn btn-primary">게시글 작성</button>
              <!-- <a href="#" class="myButton">게시글 작성</a> -->
          </div>
        </footer>        
      </body>
  </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?=DOMAIN?>/public/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="<?=DOMAIN?>/public/vender/popper.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/holder.min.js"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
    </script>
  </body>
</html>
