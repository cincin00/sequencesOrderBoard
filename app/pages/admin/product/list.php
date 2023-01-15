<?php 
  require_once('../../../../index.php');
  require_once('../../../libraries/product_lib.php');
  require_once('../../../libraries/admin_lib.php');

  $proudct = getProductForAdminList();
  
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
                    <table id="datatable-layout" class="table table-bordered table-striped" data-page-length='10'>
                        <tbody>
                        <?php 
                                if($proudct){ 
                                    foreach($proudct as $index => $data){
                                ?>
                                <tr class="text-center" >
                                    <td><?=($index+1)?></td>
                                    <td><img src="<?=PATH_COMMON_RESOURCE?>/no_image.jpg" style="padding-right:10px;width:50px;height:auto;"/><?=$data['name']??'-';?></td>
                                    <td><?=$data['price'];?></td>
                                    <td><?=$data['is_visible'];?></td>
                                    <td><?=$data['regist_date'];?></td>
                                    <td><a href="<?=ADMIN_DIR.'/product/modify.php'.'?product_id='.$data['id']?>" class="btn btn-default" role="button">상세</a></td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
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
    $('#datatable-layout').DataTable({
        order: [[ 0, 'desc' ], [ 1, 'asc' ]],
        searching: false,
        columns: [
            {title: '번호'},
            {title: '상품명'},
            {title: '판매가'},
            {title: '표시여부'},
            {title: '등록일'},
            {title: '상세', orderable: false},
        ],
        language: {
          "decimal":        "",
          "emptyTable":     "상품 정보가 존재하지 않습니다.",
          "info":           "Showing _START_ to _END_ of _TOTAL_ entries",
          "infoEmpty":      "Showing 0 to 0 of 0 entries",
          "infoFiltered":   "(filtered from _MAX_ total entries)",
          "infoPostFix":    "",
          "thousands":      ",",
          "lengthMenu":     "_MENU_ 개씩 보기",
          "loadingRecords": "로딩중...",
          "processing":     "",
          "search":         "검색:",
          "zeroRecords":    "검색 결과가 없습니다.",
          "paginate": {
              "first":      "처음",
              "last":       "마지막",
              "next":       "다음",
              "previous":   "이전"
          },
          "aria": {
              "sortAscending":  ": activate to sort column ascending",
              "sortDescending": ": activate to sort column descending"
          }
      }
    });
    </script>
</body>

</html>