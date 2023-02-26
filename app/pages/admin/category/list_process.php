<?php

  require_once('../../../../index.php');
  require_once('../../../libraries/admin_lib.php');
  require_once('../../../libraries/product_lib.php');

  // 반환값 초기화
  $response = [
    'msg' => '',
    'data' => ''
  ];
  $msg = '';
  // 전달 데이터 가공
  $params['name'] = (validSingleData($_POST, 'name') === true ? $_POST['name'] : 'New Node');
  $params['depth'] = (validSingleData($_POST, 'node_depth') === true ? $_POST['node_depth'] : 1);
  $params['depth_order'] = (validSingleData($_POST, 'node_order') === true ? $_POST['node_order'] : 0);
  $params['mode'] = (validSingleData($_POST, 'mode') === true ? $_POST['mode'] : null);
  $params['parent_code'] = (validSingleData($_POST, 'parent_code') === true ? $_POST['parent_code'] : 'root');
  $params['category_code'] = (validSingleData($_POST, 'category_code') === true ? $_POST['category_code'] : '');

  // 처리 방식 분기
  if (validSingleData($params, 'mode')) {
      if ($params['mode'] === 'create_node') {
          // 카테고리 코드 생성
          $params['category_code'] = setCategoryCode($params['depth'], $params['depth_order'], $params['parent_code']);
          // 저장
          $result = setCategory($params);
          if ($result) {
              $response['data'] = getCategoryForAdminCategoryList();
              echo $response['data'];
              exit;
          } else {
              $msg = '카테고리 추가에 실패하였습니다.';
          }
      } elseif ($params['mode'] === 'rename_node') {
          $result = renameCategory($params['name'], $params['category_code']);
          if ($result) {
              $response['data'] = getCategoryForAdminCategoryList();
              echo $response['data'];
              exit;
          } else {
              $msg = '카테고리명 수정에 실패하였습니다.';
          }
      } elseif ($params['mode'] === 'delete_node') {
          if (empty($params['category_code']) === false) {
              // 삭제 카테고리 하위 카테고리 조회
              $categoryList = getCategoryForAdminCategoryDelete($params['category_code'], $params['depth']);
              // 카테고리 삭제
              foreach ($categoryList as $category) {
                  deleteCategory($category);
              }

              $response['data'] = getCategoryForAdminCategoryList();
              echo $response['data'];
              exit;
          } else {
              $msg = '카테고리 코드 정보가 유효하지 않습니다.';
          }
      } elseif ($params['mode'] === 'move_node') {
          $parentNodeDepth = strlen($params['parent_code']);
          if ($parentNodeDepth < 4) {
              $currentDepth = strlen($params['parent_code']) + 1;
          } else {
              $currentDepth = 4;
          }
          // 신규 카테고리 코드 조회
          $newCategoryCode = setCategoryCode($currentDepth, $params['depth_order'], $params['parent_code']);
          $categoryCondtion = [
            'where' => 'category_code = "'.$newCategoryCode.'"',
            'debug' => false,
          ];
          $isExistCategory = getCategory($categoryCondtion);
          // 기존 데이터 재정렬
          if (empty($isExistCategory) === false) {
              var_dump('기존 데이터 존재');
              var_dump($isExistCategory['depth_order']);
              if($isExistCategory['depth_order'] <= 25){
                
              }
              /**
               * 재귀 방식으로 나 -> 하위 -> 다음 (반복)
               * - 현재 깊이 확인
               * - 현재 깊이를 갖고 있는 코드 정보 확인
               * - 가장 마지막 순번에 있는 코드 부터 재정렬 시작(depth_order로 알 수 있음.), 현재 순번($isExistCategory['depth_order'])까지 반복
               * - 
               * $isExistCategory['id']
               * $isExistCategory['name']
               * $isExistCategory['depth']
               * $isExistCategory['depth_order']
               * $isExistCategory['category_code']
               */
          } else {
              $insertCondtion = [
                'name' => $params['name'],
                'depth' => $currentDepth,
                'depth_order' => $params['depth_order'],
                'category_code' => $newCategoryCode,
              ];
              // 신규 카테고리 정보 갱신
              $insertRes = setCategory($insertCondtion);
              if ($insertRes === false) {
                  $msg = '카테고리 갱신 오류.';
              }
          }

          $response['data'] = getCategoryForAdminCategoryList();
          echo $response['data'];
          exit;
      } elseif ($params['mode'] === 'edit') {
      } elseif ($params['mode'] === 'copy_node') {
      } else {
          $msg = '유효하지 않는 요청입니다.';
      }
  } else {
      $msg = '요청이 올바르지 않습니다.';
  }
  $response['msg'] = $msg;

  echo json_encode($response);
