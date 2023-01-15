<?php 
  require_once('../../../../index.php');
  require_once('../../../libraries/product_lib.php');
  require_once('../../../libraries/admin_lib.php');

  $productId = validSingleData($_GET, 'product_id') ? $_GET['product_id'] : 0;

  $productCondtion = [
    'set' => 'is_delete = 1',
    'where' => 'id = '.$productId
  ];
  
  $result = updateProduct($productCondtion);

  if($result){
    $msg = '상품이 삭제되었습니다.';
    $location = ADMIN_DIR.'/product/list.php';
  } else {
    $msg = '상품 삭제를 실패하였습니다.';
    $location = ADMIN_DIR.'/product/modify.php?product_id='.$productId;
  }

  commonMoveAlert($msg, $location);
?>