@extends('admin.layout.default')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                用户管理
                <small>数据列表</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box grid-box">

                        <div class="box-header with-border">
                            <div class="pull-right">

                                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                    <a href="/admin/users/create" class="btn btn-sm btn-success" title="新增">
                                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
                                    </a>
                                </div>

                            </div>
                            <div class="pull-left">
                                <div class="btn-group" style="margin-right: 5px" data-toggle="buttons">
                                    <label class="btn btn-sm btn-dropbox filter-btn" title="筛选">
                                        <i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;筛选</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="box-header with-border filter-box hide" id="filter-box">
                            <form action="/admin/users" class="form-horizontal" method="get">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body">
                                            <div class="fields-group">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"> 序号</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-pencil"></i>
                                                            </div>
                                                            <input type="text" class="form-control id" placeholder="ID"
                                                                   name="id" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <div class="btn-group pull-left">
                                                    <button class="btn btn-info submit btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover grid-table" id="grid-table64634d9f95d1e">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>用户名称</th>
                                    <th>创建时间</th>
                                    <th>更新时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usersList as $data)
                                    <tr data-key="{{$data['id']}}">

                                        <td>{{$data['id']}}</td>
                                        <td>{{$data['username']}}</td>
                                        <td>{{$data['created_at']}}</td>
                                        <td>{{$data['updated_at']}}</td>
                                        <td>
                                            <a href="/admin/users/edit/{{$data['id']}}"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{$data['id']}}" class="tree_branch_delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <div class="box-footer clearfix">
                            共 <b>{{$totalPages}}</b> 页，每页 {{$pageNums}} 条， 共 <b>{{$total}}</b> 条
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li class="page-item @if($pageNo == 1) disabled @endif" >@if($pageNo > 1)<a class="page-link" href="/admin/users?page={{$pageNo-1}}" rel="prev">&laquo;</a>@else <span class="page-link">&laquo;</span> @endif</li>
                                @for($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item @if($pageNo == $i) active @endif">@if($i != $pageNo)<a class="page-link" href="/admin/users?page={{$i}}">{{$i}}</a>@else<span class="page-link">{{$i}}</span>@endif</li>
                                @endfor
                                <li class="page-item @if($pageNo ==$totalPages) disabled @endif">@if($pageNo != $totalPages)<a class="page-link" href="/admin/users?page={{$totalPages}}"  rel="next">»</a>@else<span class="page-link">»</span>@endif</li>
                            </ul>
                        </div>
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
            var $btn = $('.filter-btn');
            var $filter = $('#filter-box');

            $btn.unbind('click').click(function (e) {
                if ($filter.is(':visible')) {
                    $filter.addClass('hide');
                    $btn.removeClass('active')
                } else {
                    $filter.removeClass('hide');
                    $btn.addClass('active')
                }
            });

            $('.tree_branch_delete').click(function () {
                var id = $(this).data('id');
                swal({
                    title: "确认删除?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "取消",
                    preConfirm: function () {
                        return new Promise(function (resolve) {
                            $.ajax({
                                type: 'delete',
                                url: '/admin/users/delete/' + id,
                                data: {
                                    _method: 'delete'
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
                }).then(function (result) {
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

        });
    </script>
@endsection