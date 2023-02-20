<?php
  require_once('../../../../index.php');
  require_once('../../../libraries/product_lib.php');
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
                    <form id="dropzone-form" name="dropzone-form" class="dropzone form-horizontal" method="POST"
                        action="<?=ADMIN_DIR?>/product/image_upload.php" enctype="multipart/form-data"></form>
                    <form id="product-form" class="form-horizontal" action="<?=ADMIN_DIR?>/product/write_process.php"
                        method="post">
                        <input type="hidden" name="files_seq" id="files_seq" />
                        <section class="content">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    </h3>
                                    <div class="card-tools">
                                        <div>
                                            <a href="<?=ADMIN_DIR?>/product/list.php" class="btn btn-default"
                                                role="button">목록</a>
                                            <button id="btn-write" type="button" class="btn btn-primary">등록</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="previews"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name" class="col-sm-2 col-form-label">상품명</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_name" id="productName"
                                                placeholder="상품명" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">가격</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_price"
                                                id="productPrice" placeholder="가격" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">표시여부</label>
                                        <div class="col-sm-10">
                                            <select name="is_visible" id="is_visible" class="custom-select rounded-0">
                                                <option value="0"> 미표시 </option>
                                                <option value="1"> 표시 </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">상태여부</label>
                                        <div class="col-sm-10">
                                            <select name="product_status" id="product_status"
                                                class="custom-select rounded-0">
                                                <option value="0">품절</option>
                                                <option value="1">판매</option>
                                                <option value="2">예약</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_detail">상품 상세 설명</label>
                                        <textarea name="product_desc" id="product_detail"></textarea>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
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
    <!-- dropzone -->
    <script src="<?=VENDER?>/dropzone-5.9.3/dropzone.min.js"></script>
    <link rel="stylesheet" href="<?=VENDER?>/dropzone-5.9.3/dropzone.min.css" type="text/css" />
    <!-- Summernote -->
    <script src="<?=ADMIN_PLUGIN?>/summernote/summernote-bs4.min.js"></script>
    <!-- Page specific script -->
    <script>
    // Dropzone Init
    //Dropzone.autoDiscover = false;
    Dropzone.options.dropzoneForm = {
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: 5,
        // 언어 설정
        dictCancelUpload: "업로드 취소",
        dictCancelUploadConfirmation: "업로드를 취소하시겠습니까?",
        dictDefaultMessage: "상품 이미지를 업로드해주세요.(최대 5개/5MB)",
        dictFallbackMessage: "현재 브라우저에서는 드래그앤드랍을 지원하지 않습니다.",
        dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
        dictFileSizeUnits: { tb: 'TB', gb: 'GB',mb: 'MB',kb: 'KB',b: 'b' },
        dictFileTooBig: "업로드할 피일이 너무 큽니다. ({{filesize}}MiB). 최대 업로드 가능 용량: {{maxFilesize}}MiB.",
        dictInvalidFileType: "지원하지 않는 업로드 형식입니다.",
        dictMaxFilesExceeded: "최대 업로드 가능 파일 수량은 {{maxFiles}}개입니다.",
        dictRemoveFile: "파일 삭제",
        dictRemoveFileConfirmation: null,
        dictResponseError: "Server responded with {{statusCode}} code.",
        dictUploadCanceled: "파일 업로드 취소됨.",
        init: function() {
            console.log("dropzone Init");
            myDropzone = this;
            // 폼 등록 체이닝 시작
            $("#btn-write").on('click', function() {
                // 1 - 1. 큐 이미지 폼 전송 
                myDropzone.processQueue();
            });
        },
        // 1 - 2
        sendingmultiple: function() {
            console.log('sendingmultiple callback');
        },
        // 2 - 1. dropzone upload suceess
        successmultiple: function(files, response) {
            console.log('successmultiple callback');
            $("#files_seq").val(response);
            $("#product-form").submit();
        },
        // 2 - 2. dropzone upload fail
        errormultiple: function(v) {
            console.log('errormultiple callback');
        },
    }

    $(function() {
        let domain = '<?=DOMAIN?>';
        let productId = '';
        let productImgOri = '';
        let productImg = {};
        // Summernote Init
        $('#product_detail').summernote();;
    });
    </script>
</body>

</html>