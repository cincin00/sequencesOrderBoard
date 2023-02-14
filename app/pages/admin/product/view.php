<?php 
  require_once('../../../../index.php');
  require_once('../../../libraries/product_lib.php');
  require_once('../../../libraries/admin_lib.php');
  
  $product = getProductForAdminView($_GET);
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
                            <h1>상품 관리</h1>
                        </div>
                        <!-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                          <li class="breadcrumb-item active">Flot</li>
                        </ol>
                      </div> -->
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                <section class="content">
            <div class="card">
                <div class="card-header">
                <h3 class="card-title">
                    <?=$product['name']?>
                </h3>
                <div class="card-tools">
                    <div>
                      <div>
                        <a href="<?=ADMIN_DIR?>/prodouct/list.php" class="btn btn-default" role="button">목록</a>
                        <a href="<?=ADMIN_DIR?>/prodouct/list.php" class="btn btn-primary" role="button">수정</a>
                      </div>
                    </div>
                </div>
                </div>
                <form class="form-horizontal">
                 <!-- <input type="hidden" name="member_id" class="form-control" value=""> -->
                <div class="card-body">
                    <div class="form-group">
                        <label class="col-sm-2" for="writer">작성자</label>
                        <div id="writer" class="col-sm-10">
                            <p class="form-control-static">작성자명</p>
                        </div>
                        <label class="col-sm-2" for="writer">내용</label>
                        <div id="writer" class="col-sm-10">
                            <p class="form-control-static">내용 영역</p>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    
                </div>
                </form>
                <!-- /.card-footer-->
            </div>
            </section>
                </div><!-- /.container-fluid -->
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
    <!-- DataTables -->
    <link rel="stylesheet" href="<?=ADMIN_PLUGIN?>/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=ADMIN_PLUGIN?>/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?=ADMIN_PLUGIN?>/datatables-buttons/css/buttons.bootstrap4.min.css">
    <script src="<?=ADMIN_PLUGIN?>/jszip/jszip.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/pdfmake/pdfmake.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/pdfmake/vfs_fonts.js"></script>
    <!-- Page specific script -->
    <script>
    </script>
</body>

</html>