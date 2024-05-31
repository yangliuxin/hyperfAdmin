@extends('admin.layout.default')
@section('content')

    <link rel="stylesheet" href="/vendor/AdminLTE/plugins/iCheck/square/blue.css">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                脚手架
                <small>代码生成工具</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                        </div>
                        <div class="box-body">
                            <div class="box-body">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label for="table" class="col-sm-3 control-label">表名</label>
                                        <div class="col-sm-9">
                                            <select class="form-control table" id="table" name="table">
                                                <option value="" selected="selected">请选择表名</option>
                                                @foreach($tables as $table)
                                                    <option value="{{$table}}">{{$table}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="controller" class="col-sm-3 control-label">模块名称</label>

                                        <div class="col-sm-9">
                                            <input type="text" name="module_name" class="form-control" id="module_name"
                                                   placeholder="模块名称" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="model" class="col-sm-3 control-label">模型</label>

                                        <div class="col-sm-9">
                                            <input type="text" name="model" class="form-control" id="model"
                                                   placeholder="model" value="App\Model\">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="controller" class="col-sm-3 control-label">控制器</label>

                                        <div class="col-sm-9">
                                            <input type="text" name="controller" class="form-control" id="controller"
                                                   placeholder="controller" value="App\Controller\Admin\">
                                        </div>
                                    </div>
                                    <div class="form-group" style="display: flex; align-items: baseline;">
                                        <label for="build_controller" class="col-sm-3 control-label">控制器</label>
                                        <div class="col-sm-9">
                                            <label>
                                                <input type="checkbox" value="1" name="build_controller"
                                                       id="build_controller" checked="checked">
                                                建立控制器
                                            </label>
                                            <label>
                                                <input type="checkbox" value="1" name="build_model" id="build_model"
                                                       checked="checked">
                                                建立模型
                                            </label>
                                            <label>
                                                <input type="checkbox" value="1" name="build_view" id="build_view"
                                                       checked="checked">
                                                建立模板视图
                                            </label>

                                        </div>
                                    </div>
                                    <div class="form-group" style="display: none;" id="fields_div">
                                        <label for="controller" class="col-sm-3 control-label">字段设置</label>

                                        <div class="col-sm-9">
                                            <table class="table table-hover">
                                                <tbody id="table-fields">


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" id="submitOp" class="btn btn-info pull-right">提交</button>
                            </div>
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
    <script src="/vendor/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>

        function toCamelCase(str) {
            return str.replace(/[-_\s]+(.)?/g, (match, group1) => group1 ? group1.toUpperCase() : '');
        }

        function toUpperCamelCase(str) {
            return str.replace(/(?:^|[-_])(\w)/g, (_, c) => c.toUpperCase());
        }

        $(function () {

            var obj = $('#table').select2({"allowClear": false, "placeholder": {"id": "", "text": "请选择数据表"}});
            $('#table').on('change', function () {
                $('#model').val("App\\Model\\" + toUpperCamelCase($('#table').val()));
                $('#controller').val("App\\Controller\\Admin\\" + toUpperCamelCase($('#table').val()) + "Controller");
                $('#module_name').val(toUpperCamelCase($('#table').val()));
                $('#table-fields').html('')
                $.ajax({
                    type: 'post',
                    url: '/admin/api/scaffold/table',
                    data: {
                        table: $('#table').val(),
                    },
                    async: true,
                    processData: true,
                    success: function (data) {
                        if (data.code == 1) {
                            let list = data.data
                            var _html = [];
                            _html.push('<tr>');
                            _html.push('<th style="width: 200px">字段名称</th>');
                            _html.push('<th>字段类型</th>');
                            _html.push('<th>字段注释</th>');
                            _html.push('</tr>');
                            for (let i = 0; i < list.length; i++) {
                                _html.push('<tr>');
                                _html.push('    <td style="vertical-align: middle;">' + list[i] + '</td>');
                                _html.push('    <td>');
                                _html.push('        <select name="field_type[]" data-field="' + list[i] + '" class="field_type" style="width: 100%;">');
                                _html.push('            <option value="text">文本</option>');
                                _html.push('            <option value="long_text">长文本</option>');
                                _html.push('            <option value="pic">图片</option>');
                                _html.push('            <option value="album">相册</option>');
                                _html.push('            <option value="select">下拉选择</option>');
                                _html.push('            <option value="switch">开关</option>');
                                _html.push('            <option value="number">自然数</option>');
                                _html.push('        </select>');
                                _html.push('    </td>');
                                _html.push('    <td><input type="text" class="form-control field_comment" placeholder="注释" name="field_comment[]" data-field="' + list[i] + '" value="' + list[i] + '"></td>');
                                _html.push('</tr>');
                            }
                            $('#table-fields').append(_html.join(''));
                            $('.field_type').select2({"allowClear": false, "placeholder": {"id": "", "text": "数据字段类型"}});

                            $('#fields_div').css('display', 'block')

                        } else {
                            swal(data.message, '', 'error');
                        }
                    }
                });
            })

            $('#submitOp').click(function () {
                $(this).attr('disabled', 'disabled')
                if ($('#table').val() == '') {
                    swal("请选择数据表", '', 'error');
                    $(this).removeAttr('disabled')
                    return
                }
                if ($('#module_name').val() == '') {
                    swal("请输入表模块名称", '', 'error');
                    $(this).removeAttr('disabled')
                    return
                }
                var field_type = {}
                $(".field_type").each(function(index, element) {
                    field_type[$(this).data('field')] = $(this).val()
                })
                var field_comment = {}
                $(".field_comment").each(function(index, element) {
                    field_comment[$(this).data('field')] = $(this).val() ? $(this).val() : $(this).data('field')

                })
                $.ajax({
                    type: 'post',
                    url: '/admin/api/scaffold',
                    data: {
                        table: $('#table').val(),
                        model: $('#model').val(),
                        controller: $('#controller').val(),
                        build_controller: $('#build_controller').attr("checked") === "checked" ? 1 : 0,
                        build_model: $('#build_model').attr("checked") === "checked" ? 1 : 0,
                        build_view: $('#build_view').attr("checked") === "checked" ? 1 : 0,
                        field_type: JSON.stringify(field_type),
                        field_comment: JSON.stringify(field_comment),
                        module_name: $('#module_name').val()
                    },
                    async: true,
                    processData: true,
                    success: function (data) {
                        if (data.code === 1) {
                            swal("操作成功", '', 'success');
                            location.reload()
                        } else {
                            swal(data.message, '', 'error');
                            $('#submitOp').removeAttr('disabled')
                        }
                    }
                });
            })
            $('#build_controller').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            }).on('ifClicked', function (event) {
                if($(this).attr("checked") == "checked"){
                    $('#build_controller').removeAttr("checked")
                } else {
                    $('#build_controller').attr("checked", "checked")
                }
            });
            $('#build_model').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            }).on('ifClicked', function (event) {
                if($(this).attr("checked") == "checked"){
                    $('#build_model').removeAttr("checked")
                } else {
                    $('#build_model').attr("checked", "checked")
                }
            });
            $('#build_view').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            }).on('ifClicked', function (event) {
                if($(this).attr("checked") == "checked"){
                    $('#build_view').removeAttr("checked")
                } else {
                    $('#build_view').attr("checked", "checked")
                }
            });
        });
    </script>
@endsection