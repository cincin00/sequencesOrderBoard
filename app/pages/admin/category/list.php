<?php
  require_once('../../../../index.php');
  require_once('../../../libraries/product_lib.php');
  require_once('../../../libraries/admin_lib.php');

  $category = getCategoryForAdminCategoryList();
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
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">관리</h3>
                        <div class="card-tools">
                            <button type="button" id="node-new" class="btn btn-tool" title="카테고리 추가">
                                추가
                            </button>
                            <button type="button" id="node-mod" class="btn btn-tool" title="카테고리명 수정">
                                수정
                            </button>
                            <button type="button" id="node-del" class="btn btn-tool" title="카테고리 제거">
                                삭제
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="product_category_div">
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <!-- <div class="card-footer">
                        Footer
                    </div> -->
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
    let categoryRaw = '<?=$category;?>';
    let parseCategory = JSON.parse(categoryRaw);
    //console.log(parseCategory);
    $(function() {
        let jsTree = $('#product_category_div');

        // jsTree 초기화
        jsTree.jstree({
            "core": {
                "check_callback": true,
                "data": parseCategory,
            },
            "plugins": ["dnd", "types"],
            "types": {
                "valid_children": ["default"],
                "default": {
                    "max_depth": 4
                }
            }
        });

        /** [jsTree] 노드 추가 버튼 */
        $('#node-new').on('click', function() {
            // jsTree 셀렉터
            let ref = jsTree.jstree(true);
            // 선택한 노드(생성된 노드의 부모 노드)
            let sel = ref.get_selected();
            let maxCategory = sel[0].toString().length;
            if(maxCategory>3){
                alert('최대 하위 카테고리는 4개까지 가능합니다.');
                return false;
            }
            // 데이터가 없는 경우 동작 취소
            if (!sel.length) {
                return false;
            }
            // 셀렉터 재할당
            sel = sel[0];
            // 생성할 카테고리 노드 정보
            let node = {
                "type": "file",
                "code": "",
            };
            // 생성된 노드 추가할 위치(before, after, first/inside, last)
            let pos = 'last';
            // 카테고리 노드 생성 후 콜백 함수
            let callback = function(e) {
                // 카테고리 깊이
                let depth = (e.parents.length);
                // 노드 순서
                let postion = getCurrentNodePostion(ref, getCurrentNode(ref, e.parent), e);
                // 부모 노드 카테고리 코드
                let parentCode = getCurrentNode(ref, e.parent).original.category_code;
                let params = {
                    // 전송 모드
                    mode: 'create_node',
                    // 노드명
                    node_name: e.text,
                    // 노드 깊이
                    node_depth: depth,
                    // 노드 순서
                    node_order: postion,
                    // 부모 노드 카테고리 코드
                    parent_code: parentCode,
                };

                categoryHandler(ref, params);
            }
            // 카테고리 생성
            sel = ref.create_node(sel, node, pos, callback);
            // 생성된 카테고리 노드 편집 모드
            ref.edit(sel);
        });

        /** [jsTree] 노드 삭제 버튼 */
        $('#node-del').on('click', function() {
            let result = window.confirm('하위 카테고리까지 삭제됩니다.\n선택한 카테고리를 삭제하시겠습니까?');
            if(result === false){
                return false;
            }

            let ref = jsTree.jstree(true);
            let sel = ref.get_selected();

            if (!sel.length || sel == 'j1_1') {
                return false;
            }

            let currentNode = getCurrentNode(ref, sel[0]);
            let depth = (currentNode.parents.length);

            let params = {
                // 전송 모드
                mode: 'delete_node',
                category_code: sel[0],
                node_depth: depth
            };

            categoryHandler(ref, params);
            ref.delete_node(sel);
        });

        /** [jsTree] 노드 수정 버튼 */
        $('#node-mod').on('click', function() {
            let ref = jsTree.jstree(true);
            let sel = ref.get_selected();
            if(sel === 'root'){
                return false;
            }

            if (!sel.length || sel == 'j1_1') {
                return false;
            }

            let currentNode = getCurrentNode(ref, sel[0]);
            let depth = (currentNode.parents.length);

            sel = sel[0];
            let default_text = '';
            let callbackFun = function(){
                let params = {
                    // 전송 모드
                    mode: 'rename_node',
                    category_code: sel[0],
                    node_depth: depth
                };
                categoryHandler(ref, params)
            }
            ref.edit(sel, default_text, callbackFun);
        });

        /**
         * [jsTree]현재 노드의 정보 반환
         * @param {object} jtObj jstree 객체
         * @param {object} currentNodeId 확인할 jstree 객체 정보
         * @return {object}
         */
        function getCurrentNode(jtObj, currentNodeId) {
            return jtObj.get_node(currentNodeId);
        }

        /**
         * [jsTree]현재 노드의 부모 노드 반환
         * 
         * @param {object} jtObj jstree 객체
         * @param {object} currentNode jstreeNode 객체
         * @return {object}
         */
        function getCurrentNodeParents(jtObj, currentNode) {
            return jtObj.get_parent(currentNode.parent);
        }

        /**
         * [jsTree]현재 노드의 순서(Order) 반환
         * 
         * @param {object} jtObj jstree 객체
         * @param {object} parentNode 부모 노드 객체
         * @param {object} currentNode 현재 노드 객체
         * @return {int}
         */
        function getCurrentNodePostion(jtObj, parentNode, currentNode) {
            return parentNode.children.indexOf(currentNode.id);
        }

        /**
         * 카테고리 핸들러
         * 
         * @param {object} params
         */
        function categoryHandler(jsTree, params){
            // 카테고리 코드 처리 URL
            let url = '<?=ADMIN_DIR?>/category/list_process.php';

            $.post(url, params, function(res) {
                    let r = JSON.parse(res);
                    jsTree.settings.core.data = r;
                    jsTree.refresh(true);
                }).fail(function(res) {
                    let r = JSON.parse(res);
                    console.log('fail');
                    console.log(r);
                });
        }
    });
    </script>
</body>

</html>