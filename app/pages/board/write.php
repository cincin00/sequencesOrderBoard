<?php
    // 게시판 설정 로드
    $boardQuery = "SELECT * FROM board WHERE id='1'";
    $boardResult = $dbh->query($boardQuery);
    $boardData = $boardResult->fetch();

    // 게시판 카테고리 로드
    $categoryQuery = "SELECT * FROM board_category ORDER BY sort_order";
    $categoryResult = $dbh->query($categoryQuery);
    foreach ($categoryResult as $categoryData) {
        $category[$categoryData['sort_order']] = $categoryData['title'];
    }
    ?>
<div class="layout">
    <div id="board_header">
        <h1><span><?= $boardData['title'] ?></span></h1>
    </div>
    <hr/>
    <form name="board_write" action="./board/board_procss.php/" method="post">
        <table>
            <thead>
                <tr>
                    <td>
                        <select name="board_category">
                            <option value=''>카테고리 선택</option>
                            <?php foreach ($category as $index => $categoryTitle) { ?>
                                <option value='<?=$categoryTitle ?>'><?=$categoryTitle ?></option>
                            <?php } ?>
                        </select>  
                    </td>
                    <td>
                        <input type="text" name="title" placeholder="제목을 입력해주세요." require="true" maxlength="255">
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <textarea></textarea>
                    </td>
                </tr>
                <tr>
                    <td> 사용자 </td>
                    <td> <input type="text" name="writer" placeholder="사용자명을 입력해주세요."> </td>
                </tr>
                <tr>
                    <td> 비밀번호 </td>
                    <td> <input type="password" name="writer" placeholder="사용자명을 입력해주세요."> </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                        <button>취소</button>
                    </td>
                    <td>
                        <button>등록</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>