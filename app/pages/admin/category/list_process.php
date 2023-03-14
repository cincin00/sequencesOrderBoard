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
              // 이동하려는 차수의 전체 수량이 25(A-Z)의 길이를 초과하는지 검증
              if (getCountCategoryDepth($isExistCategory['depth']) >= 26) {
                  $msg = '현재 카테고리 차수에 추가할 수 있는 수량을 초과하였습니다.';
              } else {
                  /**
                   * New Logic
                   * - 현재 이동된 카테고리 코드 조회(setCategoryCode)✓
                   * - 현재 생성된 카테고리 코드가 존재하는지 검증(getCategory)✓
                   * - 최대 카테고리에 도달했는지 검증(getCountCategoryDepth)✓
                   * -- 존재하지 않는 경우
                   * --- 신규 카테고리로 데이터 갱신(setCategory)
                   * --- 하위 카테고리 데이터 갱신(setSubCategory)
                   * --- 데이터 재조회 및 반환(getCategoryForAdminCategoryList)
                   * -- 존재하는 경우(changeCategory)
                   * --- 신규 생성된 카테고리 다음 코드(N+1) 값 조회 후 존재 여부 검증
                   * --- 반복 x 신규 생성된 카테고리 존재하지 않을 때 까지!
                   * Example) A-AB-ABC => DBC(targetCategoryCode)
                   */
                  dd('EOF');
              }
          } else {
              // 이동되는 카테고리 정보 갱신
              $mainUpdateCondtion = [
                  'set' => 'category_code = "'.$newCategoryCode.'"',
                  'where' => 'category_code = "'.$params['category_code'].'"',
                  'debug' => false,
              ];
              $mainUpdateRes = updateCategory($mainUpdateCondtion);
              if ($mainUpdateRes === false) {
                  $msg = '카테고리 갱신 오류.';
                  $response['msg'] = $msg;
                  $response['data'] = getCategoryForAdminCategoryList();
                  echo json_encode($response);
                  exit;
              }

              // 갱신 처리할 하위 카테고리 코드 조회
              $subSelectCondtion = [
                  'select' => 'category_code',
                  'where' => 'category_code LIKE "'.$params['category_code'].'%"',
                  'debug' => false,
              ];
              $mainSelectRes = getCategory($subSelectCondtion, 1);
              if ($mainSelectRes) {
                  foreach ($mainSelectRes as $categoryCode) {
                      $temp[] = $categoryCode['category_code'];
                  }
              }

              // 이동되는 카테고리 하위 정보 갱신
              $subUpdateCondtion = [
                'set' => 'category_code = REPLACE(category_code, "'.$params['category_code'].'", "'.$newCategoryCode.'")',
                'where' => 'category_code IN ("'.implode('","', $temp).'")',
                //'debug' => true,
              ];
              $subUpdateRes = updateCategory($subUpdateCondtion);
              if ($subUpdateRes === false) {
                  // 하위 카테고리 갱신 실패 시 앞서 갱신한 상위 카테고리 복구
                  $rollbackMainUpdateCondtion = [
                    'set' => 'category_code = "'.$params['category_code'].'"',
                    'where' => 'category_code = "'.$newCategoryCode.'"',
                    'debug' => false,
                ];
                $rollbackMainUpdateRes = updateCategory($rollbackMainUpdateCondtion);
                  // 이동되는 카테고리 정보 갱신
                  $mainUpdateCondtion = [
                        'set' => 'category_code = "'.$newCategoryCode.'"',
                        'where' => 'category_code = "'.$params['category_code'].'"',
                        'debug' => false,
                  ];
                  $mainUpdateRes = updateCategory($mainUpdateCondtion);
                  $msg = '하위 카테고리 갱신 오류.';
                  $response['msg'] = $msg;
                  $response['data'] = getCategoryForAdminCategoryList();
                  echo json_encode($response);
                  exit;
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
