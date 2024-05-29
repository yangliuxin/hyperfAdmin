@extends('admin.layout.default')
@section('content')
    <link rel="stylesheet" href="/vendor/bootstrap-fileinput/css/fileinput.min.css">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                个人设置
                <small>编辑个人资料</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">个人设置</h3>
                        </div>

                        <form action="/admin/profile" method="post" class="form-horizontal"
                              enctype="multipart/form-data" accept-charset="UTF-8">
                            <div class="box-body">
                                <div class="fields-group">
                                    <div class="col-md-12">
                                        <div class="form-group @if(isset($error['username']))  has-error @endif">
                                            <label for="username" class="col-sm-2 asterisk control-label">用户名</label>

                                            <div class="col-sm-10">
                                                @if(isset($error['username']))
                                                    <label class="control-label"><i
                                                                class="fa fa-times-circle-o"></i> {{$error['username']}}
                                                    </label>
                                                @endif
                                                <div class="input-group">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                    <input type="text" id="username" name="username"
                                                           value="{{$user['username']}}"
                                                           class="form-control username" readonly
                                                           placeholder="请输入用户名">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group @if(isset($error['name']))  has-error @endif">
                                            <label for="name" class="col-sm-2 asterisk control-label">用户名称</label>

                                            <div class="col-sm-10">
                                                @if(isset($error['name']))
                                                    <label class="control-label"><i
                                                                class="fa fa-times-circle-o"></i> {{$error['name']}}
                                                    </label>
                                                @endif
                                                <div class="input-group">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                    <input type="text" id="name" name="name" value="{{$user['name']}}"
                                                           class="form-control name" placeholder="请输入用户名称">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group @if(isset($error['avatar_file'])) has-error @endif">
                                            <label for="avatar_file" class="col-sm-2  control-label">用户头像</label>
                                            <div class="col-sm-10">
                                                @if(isset($error['avatar_file']))
                                                    <label class="control-label"><i
                                                                class="fa fa-times-circle-o"></i> </label>
                                                @endif
                                                <input type="file" class="avatar_file"
                                                       accept="image/jpeg,image/png,image/jpeg,image/gif"
                                                       name="avatar_file"
                                                       id="avatar_file" data-show-caption="true"
                                                       data-show-preview="true">
                                            </div>
                                        </div>

                                        <div class="form-group @if(isset($error['password']))  has-error @endif">
                                            <label for="password" class="col-sm-2 asterisk control-label">密码</label>

                                            <div class="col-sm-10">
                                                @if(isset($error['password']))
                                                    <label class="control-label"><i
                                                                class="fa fa-times-circle-o"></i> {{$error['password'] ?? ''}}
                                                    </label>
                                                @endif
                                                <div class="input-group">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>
                                                    <input type="password" id="password" name="password"
                                                           value="{{$user['password']}}"
                                                           class="form-control password" placeholder="请输入密码">
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
    <script lang="javascript">
        window.options = {
            "overwriteInitial": true,
            "initialPreviewAsData": true,
            "msgPlaceholder": "选择图片",
            "browseLabel": "浏览",
            "cancelLabel": "取消",
            "showRemove": false,
            "showUpload": false,
            "showCancel": false,
            "dropZoneEnabled": false,
            "allowedFileExtensions":  ['jpg', 'png'],
            "maxFileSize" : 20000,
            "fileActionSettings": {
                "showRemove": false,
                "showDrag": false
            },
            "allowedFileTypes": ["image"]
        };

        function getFileName(path) {
            var parts = path.split('/'); // 使用反斜杠分割字符串
            return parts[parts.length - 1]; // 返回分割后的最后一部分作为文件名
        }

        $(function () {
            var avatar_options = window.options;
            var avatar = '{{$user['avatar']}}'
            if (avatar !== '' && avatar !== null) {
                var initImg = {
                    initialPreview: [ //预览图片的设置
                        avatar,
                    ]
                }
                avatar_options = {...window.options, ...initImg};
            }
            $("#avatar_file").fileinput(avatar_options);

        });
    </script>
@endsection