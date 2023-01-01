<?php
  require_once('../../../../index.php');
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
                            <h1>회원 관리</h1>
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
                    <table id="datatable-layout" class="table table-bordered table-striped">
                        <thead class="text-center">
                            <th>번호</th>
                            <th>아이디</th>
                            <th>이름</th>
                            <th>아이디</th>
                            <th>가입일</th>
                        </thead>
                        <tbody>
                            <td colspan="5" class="text-center">회원 정보가 존재하지 않습니다.</td>
                        </tbody>
                    </table>
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
    <script src="<?=ADMIN_PLUGIN?>/datatables/jquery.dataTables.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/jszip/jszip.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/pdfmake/pdfmake.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/pdfmake/vfs_fonts.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?=ADMIN_PLUGIN?>/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Page specific script -->
    <script>
    $('#datatable-layout').DataTable();
    </script>
</body>

</html>