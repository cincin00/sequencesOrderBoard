<?php
  require_once('../../../../index.php');
  require_once('../../../libraries/member_lib.php');
  require_once('../../../libraries/admin_lib.php');
  $member = getMemberForAdminView();
  
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
                            <h1>회원 관리 상세</h1>
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
                <h3 class="card-title">회원 정보</h3>
                <div class="card-tools">
                    <?php if($member['withdrawal']){ ?>
                        <button id="member_recovery" class="btn btn-primary">복구</button>
                    <?php } else { ?>
                        <button id="member_withdrawal" class="btn btn-danger">탈퇴</button>
                    <?php } ?>                    
                </div>
                </div>
                <form name="post_write" action="<?=ADMIN_DIR?>/members/modify_process.php" method="post"
                 autocomplete="off"">
                 <input type="hidden" name="member_id" class="form-control" value="<?=$member['id']?>">
                <div class="card-body">
                    <div class="form-group">
                        <label for="inputName">회원 아이디</label>
                        <input type="text" id="account_id" class="form-control" value="<?=$member['account_id']?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="inputName">회원 비밀번호</label>
                        <input type="text" id="account_password" name="account_password" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="inputName">회원 이름</label>
                        <input type="text" id="account_name" name="account_name" class="form-control" value="<?=$member['name']?>">
                    </div>
                    <div class="form-group">
                        <label for="inputName">회원 이메일</label>
                        <input type="text" id="account_email" name="account_email" class="form-control" value="<?=$member['email']?>">
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer pull-right">
                    <a href="<?=ADMIN_DIR?>/members/list.php" class="btn btn-default" role="button">목록</a>
                    <button type="submit" class="btn btn-primary">저장</button>
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
        $("#member_withdrawal").click(function(){
            $result = confirm('회원을 탈퇴 시키시겠습니까?');
            if($result)
            {
                location.href = "<?=ADMIN_DIR?>/members/delete_process.php?member_id=<?=$member['id']?>&mode=1";
            }else{
                return false;
            }            
        });

        $("#member_recovery").click(function(){
            $result = confirm('회원을 복구 시키시겠습니까?');
            if($result)
            {
                location.href = "<?=ADMIN_DIR?>/members/delete_process.php?member_id=<?=$member['id']?>&mode=0";
            }else{
                return false;
            }            
        });
    </script>
</body>

</html>