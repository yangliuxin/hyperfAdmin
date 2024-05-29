@extends('admin.layout.default')
@section('content')

<div class="content-wrapper">
<section class="content-header">
    <h1>
        菜单模块
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
                            <a href="/admin/menu" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;返回列表</span></a>
                        </div>
                    </div>
                </div>

                <form method="post" class="form-horizontal" accept-charset="UTF-8">

                    <div class="box-body">
                        <div class="fields-group">
                            <div class="col-md-12">
                                <div class="form-group  @if(isset($error['parent_id']))  has-error @endif">
                                    <label for="parent_id" class="col-sm-2  control-label">父级</label>

                                    <div class="col-sm-10">
                                        @if(isset($error['parent_id']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['parent_id']}}
                                            </label>
                                        @endif
                                        <div class="input-group" style="width: 100%;">
                                            <select class="form-control parent_id" id="parent_id" name="parent_id">
                                                <option value="0" selected="selected">ROOT</option>
                                                @includeIf("admin/menu/menu_options", ['data' => $selectMenuOptions])
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group @if(isset($error['type']))  has-error @endif">
                                    <label for="type" class="col-sm-2  control-label">类型</label>

                                    <div class="col-sm-10">
                                        @if(isset($error['type']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['type']}}
                                            </label>
                                        @endif
                                        <div class="input-group" style="width: 100%;">
                                            <select class="form-control type" id="type" name="type">
                                                <option value="1" selected="selected">菜单</option>
                                                <option value="2">权限</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group @if(isset($error['title']))  has-error @endif">
                                    <label for="title" class="col-sm-2 asterisk control-label">名称</label>
                                    <div class="col-sm-10">
                                        @if(isset($error['title']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['title']}}
                                            </label>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="title" name="title" value="{{$data['title']}}"
                                                   class="form-control title" placeholder="请输入名称">
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

                                <div class="form-group icon-row @if(isset($error['icon']))  has-error @endif">
                                    <label for="icon" class="col-sm-2 asterisk control-label">图标</label>
                                    <div class="col-sm-10">
                                        @if(isset($error['icon']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['icon']}}
                                            </label>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-bars"></i></span>
                                            <input type="text" id="icon" name="icon" value="{{$data['icon']}}" class="form-control icon" placeholder="请选择图标">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group icon-row @if(isset($error['uri']))  has-error @endif">
                                    <label for="uri" class="col-sm-2 asterisk control-label">URI</label>
                                    <div class="col-sm-10">
                                        @if(isset($error['uri']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['uri']}}
                                            </label>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                            <input type="text" id="uri" name="uri" value="{{$data['uri']}}" class="form-control uri" placeholder="请选择路径">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group icon-row @if(isset($error['sort']))  has-error @endif">
                                    <label for="icon" class="col-sm-2 asterisk control-label">排序</label>
                                    <div class="col-sm-10">
                                        @if(isset($error['sort']))
                                            <label class="control-label"><i
                                                        class="fa fa-times-circle-o"></i> {{$error['sort']}}
                                            </label>
                                        @endif
                                        <div class="input-group">
                                            <input type="text" id="sort" name="sort"  value="{{$data['sort']}}" class="form-control sort" >
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
    <script>
        $(function () {
            $('#form').attr('action', window.location.href)

            var obj = $('#parent_id').select2({"allowClear":false,"placeholder":{"id":"","text":"父级菜单"}});
            var objType = $('#type').select2({"allowClear":false,"placeholder":{"id":"","text":"分类"}});
            $('#type').on('change', function (){
                if($(this).val() === '1' ){
                    $('.icon-row').show()
                    $('.uri-row').show()
                } else {
                    $('.icon-row').hide()
                    $('.uri-row').hide()
                }
            })
            var parent_id = {{$data['parent_id']}}
            if(parent_id !== null){
                obj.val(parent_id).trigger("change")
            }
            var type = {{$data['type']}}
                objType.val(type).trigger("change")

            $('.icon').iconpicker({placement:'bottomLeft'});
            $('.sort:not(.initialized)')
                .addClass('initialized')
                .bootstrapNumber({
                    upClass: 'success',
                    downClass: 'primary',
                    center: true
                });
        });
    </script>
@endsection