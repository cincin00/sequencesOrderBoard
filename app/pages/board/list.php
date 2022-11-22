<?php
  require_once('../../../index.php');
  // 게시판 설정 로드 - 게층형 게시판 고정
  $boardQuery = "SELECT * FROM board WHERE id='1'";
  $boardResult = $dbh->query($boardQuery);
  $boardData = $boardResult->fetch();

  // 페이징 처리
  // 현재 페이지 번호(기본: 1페이지)
  $currentPage = (isset($_GET['page']) === true?$_GET['page']:1);
  // 게시글 전체 수량
  $totalRow = $dbh->query('SELECT COUNT(*) as `total_cnt` FROM post')->fetch()[0];
  // 1개 페이지에 표시할 게시글 수
  $length = 10;
  // 전체 페이지 수 - 0인 경우 1 페이지로 고정
  $totalPage = ceil($totalRow/$length) > 0 ? ceil($totalRow/$length) : 1;
  // TODO - 몇개의 블록을 표시할 건지
  $block = 4;
  // 처음 페이지
  $firstPage =  1 ;
  // 이전 페이지
  $prePage = ($currentPage - 1) > 0 ? $currentPage - 1 : 1;
  // 다음 페이지
  $nextPage = ($currentPage + 1) <= $totalPage ? $currentPage + 1 : $totalPage ;
  // 마지막 페이지
  $lastPage = $totalPage;
  //현재 시작 게시글 번호
  $startRow = ($currentPage - 1) * $length;

  // 게시글 정보 조회 - 계층형 게시글 정렬 및 페이징 처리
  $postQuery = "SELECT `po`.*, `bc`.title as `category_title` FROM post as `po` LEFT JOIN board_category as `bc` ON `po`.board_category = `bc`.id  WHERE `po`.is_delete = 0 ORDER BY `po`.group_id DESC, `po`.group_order ASC, `po`.group_depth DESC LIMIT ".$startRow.", ".$length."";
  $postResult = $dbh->query($postQuery);
  $postData = $postResult->fetchAll();
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
      <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
          <div class="col-4 text-center">
            <a class="blog-header-logo text-dark" href="<?=BOARD_DIR?>/list.php"><h1><?=$boardData['title']?></h1></a>
          </div>
        </div>
        <body>
      </header>
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
            <?php
                if ($postData) {
                  foreach ($postData as $row) {
                ?>
            <tr>
              <td class="board-list-table-baqh">
                <?=$row['id']?>
              </td>
              <td class="board-list-table-baqh">
                <?=$row['category_title']?>
              </td>
              <td class="board-list-table-baqh" style="text-align:left;">
              <?php for($i=0;$i<$row['group_depth'];$i++){
                echo '&nbsp;&nbsp;-';
              }?>
                <a href="<?=BOARD_DIR?>/view.php?board_id=1&id=<?=$row['id']?>">
                  <?=$row['title']?>
                </a>                
              </td>
              <td class="board-list-table-baqh">
                <?=$row['writer']?>
              </td>
              <td class="board-list-table-baqh">
                <?=$row['regist_date']?>
              </td>
              <td class="board-list-table-baqh">
                <?=$row['hits']?>
              </td>`
            </tr>
            <?php
                }
              } else {
            ?>
            <tr>
              <td class="board-list-table-baqh" colspan="6">등록된 게시글이 없습니다.</td>
            </tr>
            <?php
                }
            ?>
          </tbody>
        </table>
        <footer>
          <div class="pos-right">
              <a href="<?=BOARD_DIR?>/write.php?board_id=<?=$boardData['id']?>">
                <button type="button" class="btn btn-primary" id="btn-write-post">게시글 작성</button>
              </a>
          </div>
          <div id="paging" class="mar-top-large" style="text-align:center;">
            <a href="<?=BOARD_DIR?>/list.php?page=<?=$firstPage?>" id="first">[처음]</a>
            <a href="<?=BOARD_DIR?>/list.php?page=<?=$prePage?>" id="prev">[이전]</a>
            <?php for($i=1;$i<=$totalPage;$i++){ ?>
            <a href="<?=BOARD_DIR?>/list.php?page=<?=$i?>" id="page" data-page="<?=$i?>"><?='['.$i.']'?></a>
            <?php } ?>
            <a href="<?=BOARD_DIR?>/list.php?page=<?=$nextPage?>" id="next">[다음]</a>
            <a href="<?=BOARD_DIR?>/list.php?page=<?=$lastPage?>" id="last">[마지막]</a>
          </div>
        </footer>        
      </body>
  </div>
    <!-- Javascript File-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?=DOMAIN?>/public/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="<?=DOMAIN?>/public/vender/popper.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/holder.min.js"></script>
    <script src="<?=DOMAIN?>/public/js/board/list.js?ver=<?=date('YmdHis')?>"></script>
    <script>
      /** Holder JS */
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
      /** List JS */
      let list = new List();
    </script>
  </body>
</html>
