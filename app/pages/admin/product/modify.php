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
                    <form class="form-horizontal" name="product_update" method="post"
                        action="<?=ADMIN_DIR?>/product/modify_process.php">
                        <section class="content">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    </h3>
                                    <div class="card-tools">
                                        <div>
                                            <div>
                                                <a href="<?=ADMIN_DIR?>/product/delete_process.php?product_id=<?=$product['id']?>"
                                                    class="btn btn-danger" role="button">삭제</a>
                                                <a href="<?=ADMIN_DIR?>/product/list.php" class="btn btn-default"
                                                    role="button">목록</a>
                                                <button type="submit" class="btn btn-primary">수정</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="product_id" id="product_id" value="<?=$product['id']?>">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div id="productDropzone" class="dropzone col-sm-12">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="product_name" class="col-sm-2 col-form-label">상품명</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_name" id="productName"
                                                placeholder="상품명" value="<?=$product['name']?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">가격</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="product_price"
                                                id="productPrice" placeholder="가격" value="<?=$product['price']?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">표시여부</label>
                                        <div class="col-sm-10">
                                            <select name="is_visible" id="is_visible" class="custom-select rounded-0">
                                                <option value="0"
                                                    <?=($product['is_visible'] === 0 ? 'selected' : ''); ?>>
                                                    미표시
                                                </option>
                                                <option value="1"
                                                    <?=($product['is_visible'] === 1 ? 'selected' : ''); ?>>
                                                    표시
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-2 col-form-label">상태여부</label>
                                        <div class="col-sm-10">
                                            <select name="product_status" id="product_status"
                                                class="custom-select rounded-0">
                                                <option value="0" <?=($product['status'] === 0 ? 'selected' : ''); ?>>품절
                                                </option>
                                                <option value="1" <?=($product['status'] === 1 ? 'selected' : ''); ?>>판매
                                                </option>
                                                <option value="2" <?=($product['status'] === 2 ? 'selected' : ''); ?>>예약
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_detail">상품 상세 설명</label>
                                        <textarea name="product_desc"
                                            id="product_detail"><?=htmlspecialchars_decode($product['description'])?></textarea>
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
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <!-- Summernote -->
    <script src="<?=ADMIN_PLUGIN?>/summernote/summernote-bs4.min.js"></script>
    <!-- Page specific script -->
    <script>
    Dropzone.autoDiscover = false;
    $(function() {
        let domain = '<?=DOMAIN?>';
        let productId = '<?=$product['id']?>';
        let productImgOri = '<?=json_encode($product['product_img']);?>';
        let productImg = JSON.parse(productImgOri);

        // Summernote Init
        $('#product_detail').summernote();
        // Dropzone Init        
        $('#productDropzone').dropzone({
            //자동 업로드
            //autoProcessQueue: false,
            //전송받는 파일 파라미터명            
            paramName: "file",
            //다중 파일 업로드                  
            uploadMultiple: true,
            //동시 업로드 파일 개수               
            parallelUploads: 1,
            //최대 파일 사이즈 (MB)                
            maxFilesize: 5,
            //첨부 개수
            maxFiles: 5,
            //미리보기 텍스트 설정
            dictDefaultMessage: '상품 이미지를 업로드해주세요.(최대 5개/5MB)',
            // 업로드 확장자 제한
            acceptedFiles: 'image/*',
            // 통신 URL 
            url: '<?=ADMIN_DIR?>/product/image_upload.php?product_id=' + productId,
            //validation을 여기서 설정하면 된다.
            accept: function(file, done) {
                let imgLimit = this.options.maxFiles;
                let imgCnt = productImg.length;
                if (imgCnt < imgLimit) {
                    // URL로 전송
                    done();
                } else {
                    // 업로드 파일 드랍존 영역에서 삭제                    
                    this.removeFile(file);
                    alert('최대 업로드 가능한 이미지는 ' + imgLimit + '개 입니다.');
                }
            },
            //서버로 파일이 전송되면 실행되는 함수
            init: function() {
                //Populate any existing thumbnails
                if (productImg) {
                    for (let i = 0; i < productImg.length; i++) {
                        let imgUrl = domain + productImg[i].path;
                        let mockFile = {
                            name: productImg[i].uuid + '_' + productImg[i].origin_name,
                            size: productImg[i].size,
                            type: productImg[i].extension,
                            status: Dropzone.ADDED,
                            url: imgUrl
                        };
                        this.files.push(mockFile);
                        this.displayExistingFile(mockFile, imgUrl);
                    }
                }
                this.on('success', function(file, responseText) {
                    //obj 객체를 확인해보면 서버에 전송된 후 response 값을 확인할 수 있다.
                    let obj = JSON.parse(responseText);
                    console.log(obj);
                });
                this.on("addedfile", function(file) {});
            }
        });


    });
    </script>
</body>

</html>