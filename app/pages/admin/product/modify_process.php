<?php 
  require_once('../../../../index.php');
  require_once('../../../libraries/product_lib.php');
  require_once('../../../libraries/admin_lib.php');

  $productId = (isset($_POST['product_id']) === true ? $_POST['product_id'] : 0);
  $productName = (isset($_POST['product_name']) === true ? $_POST['product_name'] : '');
  $productPrice = (isset($_POST['product_price']) === true ? $_POST['product_price'] : 0);
  $productDesc = (isset($_POST['product_desc']) === true ? $_POST['product_desc'] : null);
  $isVisible = (isset($_POST['is_visible']) === true ? $_POST['is_visible'] : 0);
  $productStatus = (isset($_POST['product_status']) === true ? $_POST['product_status'] : 0);

  $productCondtion = [
    'set' => 'name = "'.$productName.'", description = "'.htmlentities($productDesc).'", price = '.$productPrice.', status = '.$productStatus.', is_visible = '.$isVisible.', modify_date = "'.date('Y-m-d H:i:s').'"',
    'where' => 'id = '.$productId,
    //'debug' => true
  ];
  $result = updateProduct($productCondtion);

  if($result){
    $msg = '상품 수정이 완료되었습니다. ';
    $location = ADMIN_DIR.'/product/modify.php?product_id='.$productId;
  } else {
    $msg = '상품 수정에 실패하였습니다.';
    $location = ADMIN_DIR.'/product/modify.php?product_id='.$productId;
  }

  commonMoveAlert($msg, $location);
?>