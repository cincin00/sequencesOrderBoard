<?php
    require_once('../../../../index.php');
    require_once('../../../libraries/board_lib.php');
    require_once('../../../libraries/admin_lib.php');
    $post = getPostForAdminView();
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once('../layout/admin_head.php'); ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once('../layout/admin_header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('../layout/admin_side.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>게시글 관리 상세</h1>
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <?php if(!$post['is_delete']){ ?>
                          <li class="" style="padding-right:10px;"><a role="button" class="btn btn-default" href="<?=ADMIN_DIR?>/boards/modify.php?post_id=<?=$post['id']?>">수정</a></li>
                          <li class=""><button class="btn btn-danger" id="delete_post" >삭제</button></li>
                        <?php } ?>
                        </ol>
                      </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
            <div class="card">
                <div class="card-header">
                <h3 class="card-title">
                    <?php
                        if($post['category_title']){
                            echo '['.$post['category_title'].']';
                        }
                        echo $post['title'];
                    ?>
                </h3>
                <div class="card-tools">
                    <div>
                        <label for="hits">조회수</label>
                        <span id="hits" class="form-control-static"><?=$post['hits']?></span>
                        <label for="hits">| 작성일</label>
                        <span id="hits" class="form-control-static"><?=$post['regist_date']?></span>
                    </div>
                </div>
                </div>
                <form class="form-horizontal">
                 <!-- <input type="hidden" name="member_id" class="form-control" value=""> -->
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-sm-2" for="writer">작성자</label>
                        <div id="writer" class="col-sm-10">
                            <p class="form-control-static"><?=$post['writer']?></p>
                        </div>
                        <label class="col-sm-2" for="writer">내용</label>
                        <div id="writer" class="col-sm-10">
                            <p class="form-control-static"><?=$post['contents']?></p>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="<?=ADMIN_DIR?>/boards/list.php" class="btn btn-default" role="button">목록</a>
                </div>
                </form>
                <!-- /.card-footer-->
            </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php require_once('../layout/admin_footer.php'); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?=ADMIN_PLUGIN?>/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?=ADMIN_PLUGIN?>/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
        $("#delete_post").click(function(){
            $result = confirm('게시글을 삭제 시키시겠습니까?');
            if($result)
            {
                location.href = "<?=ADMIN_DIR?>/boards/delete_process.php?post_id=<?=$post['id']?>";
            }else{
                return false;
            }            
        });
    </script>
</body>

</html>