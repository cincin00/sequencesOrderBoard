<?php
  require_once('../../../index.php');
  $product = getProductForSkinList();
?>
<!doctype html>
<html lang="en">
<?php require_once('../header.php'); ?>

<body>
    <div class="container">
        <!-- 게시판 목록 헤더 -->
        <div class="blog-header py-3">
            <?php include_once('../layout/layout_header.php');?>
            <!-- 게시판명 -->
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark" href="<?=BOARD_DIR;?>/list.php">
                        <h1>상품 목록</h1>
                    </a>
                </div>
            </div>
        </div>
        <div class="blog-body py-3">
            <?php 
                if($product){ 
                    foreach($product as $index => $data){
            ?>
            <div class="product-item">
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                    <img src="<?=$data['img_path'];?>" alt="<?=$data['name'];?>">
                    <div class="caption">
                        <h3><?=$data['name'];?></h3>
                        <!-- <p><?=$data['description'];?></p> -->
                        <p><a href="#" class="btn btn-primary" role="button">구매</a> <a href="#" class="btn btn-default" role="button">장바구니</a></p>
                        <p class="btn-area"><a href="<?=DOMAIN?>/app/pages/product/view.php?product_id=<?=$data['id'];?>" class="btn btn-default" role="button">보기</a></p>
                    </div>
                    </div>
                </div>
            </div>
            <?php 
                    }
                } 
            ?>
        </div>
    </div>
    <!-- CSS -->
    <style>
        .blog-body {
            margin: 0px;
            padding-top: 15px;
        }
        .row {
            display: block;
        }
        .product-item {
            display: inline;
        }
        .btn-area {
            text-align: right;
        }
    </style>
    <!-- Javascript File-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script>
        window.jQuery || document.write('<script src="<?=DOMAIN?>/public/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="<?=DOMAIN?>/public/vender/popper.min.js"></script>
    <script src="<?=DOMAIN?>/public/vender/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    <script></script>
</body>

</html>