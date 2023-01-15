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

                                </h3>
                                <div class="card-tools">
                                    <div>
                                        <div>
                                            <a href="<?=ADMIN_DIR?>/prodouct/list.php" class="btn btn-default"
                                                role="button">목록</a>
                                            <a href="<?=ADMIN_DIR?>/prodouct/list.php" class="btn btn-primary"
                                                role="button">수정</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">상품명</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail3"
                                                placeholder="상품명" value="<?=$product['name']?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">가격</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="productPice"
                                                placeholder="가격" value="<?=$product['price']?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">표시여부</label>
                                        <div class="col-sm-10">
                                            <select name="" id="" class="custom-select rounded-0">
                                                <option value="0" selected="<?php ($product['is_visible'] == 0 ? true:'') ?>">미표시</option>
                                                <option value="1" selected="<?php ($product['is_visible'] == 1 ? true:'') ?>" >표시</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">상태여부</label>
                                        <div class="col-sm-10">
                                            <select name="" id="" class="custom-select rounded-0">
                                                <option value="0" selected="<?php ($product['status'] == 0? true:'') ?>">품절</option>
                                                <option value="1" selected="<?php ($product['status'] == 1? true:'') ?>">판매</option>
                                                <option value="2" selected="<?php ($product['status'] == 2? true:'') ?>">예약</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="productDetail">상품 상세 설명</label>
                                        <textarea name="description"
                                            id="productDetail"><?=$product['description']?></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center"></div>
                <!-- /.card-footer-->
            </section>
        </div>
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
    <!-- Summernote -->
    <script src="<?=ADMIN_PLUGIN?>/summernote/summernote-bs4.min.js"></script>
    <!-- Page specific script -->
    <script>
    $(function() {
        // Summernote
        $('#productDetail').summernote()
    });
    </script>
</body>

</html>