@extends('admin.layout.default')
@section('content')
    <style>
        .glyphicon{
            color: orange;
        }
    </style>
<div class="content-wrapper">
<section class="content-header">
    <h1>
        角色管理
        <small>新增</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">新增</h3>

                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 5px">
                            <a href="/admin/roles" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;返回列表</span></a>
                        </div>
                    </div>
                </div>

                <form method="post" class="form-horizontal" accept-charset="UTF-8">

                    <div class="box-body">
                        <div class="fields-group">
                            <div class="col-md-12">
                                <div class="form-group @if(isset($error['name']))  has-error @endif">
                                    <label for="name" class="col-sm-2 asterisk control-label">名称</label>
                                    <div class="col-sm-10">
                                        @if(isset($error['name']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['name']}}
                                            </label>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="name" name="name" value="{{$data['name']}}"
                                                   class="form-control name" placeholder="请输入名称">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group @if(isset($error['slug']))  has-error @endif">
                                    <label for="slug" class="col-sm-2 asterisk control-label">SLUG</label>
                                    <div class="col-sm-10">
                                        @if(isset($error['slug']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['slug']}}
                                            </label>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="slug" name="slug" value="{{$data['slug']}}"
                                                   class="form-control slug" placeholder="请输入名称">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group @if(isset($error['permissions']))  has-error @endif">
                                    <label for="permissions" class="col-sm-2 asterisk control-label">设置权限</label>

                                    <div class="col-sm-10">
                                        @if(isset($error['permissions']))
                                        <label class="control-label"><i
                                                    class="fa fa-times-circle-o"></i> {{$error['slug']}}</label>
                                        @endif
                                        <div class="input-group">
                                            <div id="permissions-div">

                                            </div>
                                            <input type="hidden" id="permissions" name="permissions" value="{{$data['permissions']}}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">

                        <div class="col-md-2">
                        </div>

                        <div class="col-md-8">

                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="{{$_token}}">
                </form>
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
<script src="/vendor/jstree/jstree.js"></script>

    <script>
        window.SELECT_NODE = {!! $permissions !!}
        $(function () {
            $('#form').attr('action', window.location.href)

            $('#permissions-div').jstree({
                "core": {
                    "multiple": true,   //单选或多选
                    "themes": {
                        "dots": true      //点之间连接线
                    },
                    "check_callback": true, //启用所有引用插件
                    "data": {!! $treeData !!},
                },

                "plugins": ["checkbox"]
            })
                .on('ready.jstree', function (){
                    $.each(window.SELECT_NODE, function (i, id){
                        console.log(i)
                        console.log(id)
                        $('#permissions-div').jstree('select_node', id.toString());
                    })
                })
                .on('changed.jstree', function (event, data) {
                    var i, j, r = [];
                    for (i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                    }
                    window.SELECT_NODE = r
                    console.log('Selected: ' + window.SELECT_NODE.join(', '))
                    $('#permissions').val(r)
                });
        });
    </script>
@endsection