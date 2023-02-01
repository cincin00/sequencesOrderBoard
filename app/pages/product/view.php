<?php
    require_once('../../../index.php');
?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <!-- Site wrapper -->
    <div class="wrapper">
      <!-- 게시판 목록 헤더 -->
      <div class="blog-header py-3">
            <?php include_once('../layout/layout_header.php');?>
            <!-- 게시판명 -->
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR;?>/list.php">
                        <h1>상품 상세</h1>
                    </a>
                </div>
            </div>
        </div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-solid">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <h3 class="d-inline-block d-sm-none">상품명(모바일)</h3>
                                <div class="col-12">
                                    <img src="<?=ADMIN_DIST?>/img/prod-1.jpg" class="product-image" alt="Product Image">
                                </div>
                                <div class="col-12 product-image-thumbs">
                                    <div class="product-image-thumb active"><img src="<?=ADMIN_DIST?>/img/prod-1.jpg"
                                            alt="Product Image"></div>
                                    <div class="product-image-thumb"><img src="<?=ADMIN_DIST?>/img/prod-2.jpg"
                                            alt="Product Image"></div>
                                    <div class="product-image-thumb"><img src="<?=ADMIN_DIST?>/img/prod-3.jpg"
                                            alt="Product Image"></div>
                                    <div class="product-image-thumb"><img src="<?=ADMIN_DIST?>/img/prod-4.jpg"
                                            alt="Product Image"></div>
                                    <div class="product-image-thumb"><img src="<?=ADMIN_DIST?>/img/prod-5.jpg"
                                            alt="Product Image"></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <h3 class="my-3">상품명(PC)</h3>
                                <p>상품 요약</p>

                                <hr>
                                <div class="bg-gray py-2 px-3 mt-4">
                                    <h2 class="mb-0">
                                        상품 가격
                                    </h2>
                                </div>₩

                                <div class="mt-4">
                                    <div class="btn btn-default btn-lg btn-flat">
                                        <i class="fas fa-heart fa-lg mr-2"></i>
                                        장바구니
                                    </div>
                                    <div class="btn btn-primary btn-lg btn-flat">
                                        <i class="fas fa-cart-plus fa-lg mr-2"></i>
                                        구매
                                    </div>
                                </div>

                                <div class="mt-4 product-share">
                                    <a href="#" class="text-gray">
                                        <i class="fab fa-facebook-square fa-2x"></i>
                                    </a>
                                    <a href="#" class="text-gray">
                                        <i class="fab fa-twitter-square fa-2x"></i>
                                    </a>
                                    <a href="#" class="text-gray">
                                        <i class="fas fa-envelope-square fa-2x"></i>
                                    </a>
                                    <a href="#" class="text-gray">
                                        <i class="fas fa-rss-square fa-2x"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="row mt-4">
                            <nav class="w-100">
                                <div class="nav nav-tabs" id="product-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab"
                                        href="#product-desc" role="tab" aria-controls="product-desc"
                                        aria-selected="true">상품 상세</a>
                                    <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab"
                                        href="#product-comments" role="tab" aria-controls="product-comments"
                                        aria-selected="false">상품 문의</a>
                                    <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab"
                                        href="#product-rating" role="tab" aria-controls="product-rating"
                                        aria-selected="false">상품 후기</a>
                                </div>
                            </nav>
                            <div class="tab-content p-3" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="product-desc" role="tabpanel"
                                    aria-labelledby="product-desc-tab"> 상품상세 설명 </div>
                                <div class="tab-pane fade" id="product-comments" role="tabpanel"
                                    aria-labelledby="product-comments-tab"> 상품 문의 </div>
                                <div class="tab-pane fade" id="product-rating" role="tabpanel"
                                    aria-labelledby="product-rating-tab"> 상품 후기 </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=ADMIN_DIST?>/css/adminlte.min.css">
    <style>
      .content-wrapper {
        margin: 0px !important;
      }
    </style>
    <!-- jQuery -->
    <script src="<?=ADMIN_PLUGIN?>/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?=ADMIN_PLUGIN?>/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?=ADMIN_DIST?>/js/adminlte.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.product-image-thumb').on('click', function() {
            var $image_element = $(this).find('img')
            $('.product-image').prop('src', $image_element.attr('src'))
            $('.product-image-thumb.active').removeClass('active')
            $(this).addClass('active')
        })
    })
    </script>
</body>

</html>