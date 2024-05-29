@extends('admin.layout.default')
@section('content')
    <link rel="stylesheet" href="/vendor/nestable/nestable.css">
    <link rel="stylesheet" href="/vendor/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="/vendor/toastr/build/toastr.min.css">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                菜单管理
                <small>数据列表</small>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header">

                            <div class="btn-group">
                                <a class="btn btn-primary btn-sm tree-menu-tree-tools" data-action="expand" title="展开">
                                    <i class="fa fa-plus-square-o"></i>&nbsp;展开
                                </a>
                                <a class="btn btn-primary btn-sm tree-menu-tree-tools" data-action="collapse" title="收起">
                                    <i class="fa fa-minus-square-o"></i>&nbsp;收起
                                </a>
                            </div>

                            <div class="btn-group">
                                <a class="btn btn-warning btn-sm tree-menu-refresh" title="刷新"><i class="fa fa-refresh"></i><span class="hidden-xs">&nbsp;刷新</span></a>
                            </div>


                            <div class="btn-group">

                            </div>


                            <div class="btn-group pull-right">
                                <a  class="btn btn-success btn-sm" href="/admin/menu/create"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;新增</span></a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <div class="dd" id="tree-menu">
                                @if(count($menuList) > 0)
                                <ol class="dd-list">
                                    @include('admin.menu.sidebar_menu', $menuList)
                                </ol>
                                @endif
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
            </div>
        </section>

    </div>
    <script src="/vendor/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/dist/js/adminlte.min.js"></script>
    <script src="/vendor/js/app.js"></script>
    <script src="/vendor/nestable/jquery.nestable.js"></script>
    <script src="/vendor/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/vendor/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>
    <script src="/vendor/number-input/bootstrap-number-input.js"></script>
    <script src="/vendor/jstree/jstree.js"></script>
    <script src="/vendor/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js"></script>
    <script src="/vendor/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/vendor/nestable/jquery.nestable.js"></script>
    <script src="/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/vendor/toastr/build/toastr.min.js"></script>
    <script>
        $(function () {
            $('#tree-menu').nestable([]);

            $('.tree_branch_delete').click(function() {
                var id = $(this).data('id');
                swal({
                    title: "确认删除?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "取消",
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            $.ajax({
                                type: 'delete',
                                url: '/admin/menu/delete/' + id,
                                data: {
                                    _method:'delete'
                                },
                                async: true,
                                processData: true,
                                success: function (data) {
                                    toastr.success('删除成功 !');
                                    resolve(data);
                                }
                            });
                        });
                    }
                }).then(function(result) {
                    var data = result.value;
                    if (typeof data === 'object') {
                        if (data.code) {
                            swal(data.message, '', 'success');
                            location.reload()
                        } else {
                            swal(data.message, '', 'error');
                        }
                    }
                });
            });



            $('.tree-menu-refresh').click(function () {
                location.reload();
            });

            $('.tree-menu-tree-tools').on('click', function(e){
                var action = $(this).data('action');
                if (action === 'expand') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse') {
                    $('.dd').nestable('collapseAll');
                }
            });

        });
    </script>
@endsection