<?php
  require_once('../../../index.php');
  $result = getPostForList($_GET);  
?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>
<body>
    <div class="container">
        <!-- 게시판 목록 헤더 -->
        <div class="blog-header py-3">
            <?php include_once('../layout/layout_header.php');?>
            <!-- 게시판명 -->
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR;?>/list.php">
                        <h1><?=$result['board_data']['title'];?></h1>
                    </a>
                </div>
            </div>
        </div>
        <!-- 검색어 -->
        <form action="<?=BOARD_DIR?>/list.php" method="get">
            <div class="mar-medium">
                <input type="text" name="keyword" class="form-control pos-right" id="keyword" style="width:300px;"
                    placeholder="검색어를 입력해주세요(제목,내용,작성자)" autocomplete="off"
                    value="<?php if(isset($_GET['keyword'])){echo $_GET['keyword'];}?>">
                <button type="submit" class="btn btn-default pos-right">검색</button>
            </div>
        </form>
        <div class="pad-top-large h5"> <?=$result['current_page'].' / '.$result['total_page'].' 페이지 (전체: '.$result['total_row'].'개)';?> </div>
        <!-- 게시글 목록 -->
        <table class="board-list-table">
            <colgroup>
                <col />
                <col />
                <col />
                <col />
                <col />
                <col />
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
                    // 게시글 데이터가 있는 경우
                    if ($result['post_data']) {
                        // 게시글번호 = 전체 게시글 수량 - 시작 게시글 번호
                        $index = $result['total_row'] - $result['start_row'];
                        foreach ($result['post_data'] as $row) {
                ?>
                <tr>
                    <!-- 게시글번호 -->
                    <td class="board-list-table-baqh">
                        <?=$index?>
                    </td>
                    <!-- 게시글 카테고리 -->
                    <td class="board-list-table-baqh">
                        <?=$row['category_title']??' - ';?>
                    </td>
                    <!-- 게시글 제목 -->
                    <td class="board-list-table-baqh" style="text-align:left;">
                        <?php for ($i=0;$i<$row['group_depth'];$i++) {
                            echo '&#9;&#9;└';
                        }?>
                        <a href="<?=BOARD_DIR?>/view.php?board_id=1&id=<?=$row['id'];?>">
                            <?=$row['title'];?>
                        </a>
                    </td>
                    <!-- 게시글 작성자 -->
                    <td class="board-list-table-baqh">
                        <span style="display:-webkit-inline-box;">
                            <?php
                            echo $row['writer'];
                        if ($row['member_id']) {
                            echo '<img src="'.PATH_COMMON_RESOURCE.'/profile-user.png" style="width:20px;">';
                        }
                        ?>
                        </span>
                    </td>
                    <!-- 게시글 등록일 -->
                    <td class="board-list-table-baqh">
                        <?=$row['regist_date']?>
                    </td>
                    <!-- 게시글 조회수 -->
                    <td class="board-list-table-baqh">
                        <?=$row['hits']??0?>
                    </td>
                </tr>
                <?php
                    // 게시글 번호는 -1 씩 감소
                    $index--;
                    }
                } else {
                    // 게시글 데이터가 없는 경우
                    ?>
                <tr>
                    <td class="board-list-table-baqh" colspan="6">등록된 게시글이 없습니다.</td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <!-- 게시판 목록 푸터 -->
        <div style="font-size:12px;">
            <div class="pos-right">
                <a href="<?=BOARD_DIR?>/write.php?board_id=<?=$result['board_data']['id']?>">
                    <button type="button" class="btn btn-primary" id="btn-write-post">게시글 작성</button>
                </a>
            </div>
            <!-- 페이징 -->
            <div id="paging" class="mar-top-large" style="text-align:center;">
                <a class="btn btn-default" href="<?=$result['first_page_url']?>" id="first">처음</a>
                <a class="btn btn-default" href="<?=$result['pre_page_url']?>" id="prev">이전</a>
                <?php foreach($result['current_page_url']['url'] as $index => $pageUrl){ ?>
                <a class="btn <?=$result['current_page'] == $result['current_page_url']['page'][$index] ? 'btn-primary' : 'btn-default'; ?>" href="<?=$pageUrl?>"
                    id="page" data-page="<?=($index + 1)?>"><?=$result['current_page_url']['page'][$index]?></a>
                <?php } ?>
                <a class="btn btn-default" href="<?=$result['next_page_url']?>" id="next">다음</a>
                <a class="btn btn-default" href="<?=$result['last_page_url']?>" id="last">마지막</a>
            </div>
        </div>
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