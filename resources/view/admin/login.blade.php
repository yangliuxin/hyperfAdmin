<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登录 | 后台管理</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/vendor/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/vendor/AdminLTE/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/vendor/AdminLTE/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page" >
<div class="login-box">
    <div class="login-logo">
        <a href="/admin/"><b>后台管理</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><br ></p>

        <form action="/admin/login" method="post">
            <div class="form-group has-feedback @if(isset($error['username']))  has-error @endif">

                @if(isset($error['username']))
                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$error['username']}}</label><br>
                @endif

                <input type="text" class="form-control" placeholder="用户名" name="username" value="">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback @if(isset($error['password']))  has-error @endif">

                @if(isset($error['password']))
                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$error['password']}}</label><br>
                @endif

                <input type="password" class="form-control" placeholder="密码" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" id="remember" value="1">
                            记住我
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
            <input type="hidden" name="csrf_token" value="{{$_token}}">
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="/vendor/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/vendor/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/vendor/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('#remember').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        }).on('ifClicked', function (event) {
            if($(this).attr("checked") == "checked"){
                $('#remember').removeAttr("checked")
            } else {
                $('#remember').attr("checked", "checked")
            }
        });;
    });
</script>
</body>
</html>
