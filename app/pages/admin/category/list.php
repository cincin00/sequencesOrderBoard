<?php
  require_once('../../../../index.php');
  require_once('../../../libraries/admin_lib.php');  
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
                            <h1>카테고리 관리</h1>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Title</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                      <div id="jstree_demo_div">
                        <ul>
                          <li>Root node 1
                            <ul>
                              <li id="child_node_1">Child node 1</li>
                              <li>Child node 2</li>
                            </ul>
                          </li>
                          <li>Root node 2</li>
                        </ul>
                      </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        Footer
                    </div>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <!-- jQuery -->
    <script src="<?=ADMIN_PLUGIN?>/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?=ADMIN_PLUGIN?>/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?=ADMIN_DIST?>/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <!-- Page specific script -->
    <script>
      $(function () { 
        $('#jstree_demo_div').jstree(); 
        $('#jstree_demo_div').on("changed.jstree", function (e, data) {
          console.log(data.selected);
        });
        $('button').on('click', function () {
          $('#jstree').jstree(true).select_node('child_node_1');
          $('#jstree').jstree('select_node', 'child_node_1');
          $.jstree.reference('#jstree').select_node('child_node_1');
        });
      });
    </script>
</body>

</html>