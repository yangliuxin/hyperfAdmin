<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>登录 | 后台管理</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="/vendor/AdminLTE/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE/plugins/iCheck/square/blue.css">
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        html {
            height: 100%;
        }
        body {
            height: 100%;
        }
        .login-container {
            height: 100%;
            background-image: linear-gradient(to right, #fbc2eb, #a6c1ee);
        }
        .login-wrapper {
            background-color: #fff;
            width: 358px;
            height: 588px;
            border-radius: 15px;
            padding: 0 50px;
            position: relative;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .header {
            font-size: 38px;
            font-weight: bold;
            text-align: center;
            line-height: 200px;
        }
        .input-item {
            display: block;
            width: 100%;
            margin-bottom: 20px;
            border: 0;
            padding: 10px;
            border-bottom: 1px solid rgb(128, 125, 125);
            font-size: 15px;
            outline: none;
        }
        .input-item:placeholder {
            text-transform: uppercase;
        }
        .btn {
            text-align: center;
            padding: 10px;
            width: 100%;
            margin-top: 40px;
            background-image: linear-gradient(to right, #a6c1ee, #fbc2eb);
            color: #fff;
        }
    </style>
</head>
<body class="hold-transition login-page" >
<div class="login-container">
    <div class="login-wrapper">
        <div class="header">后台登录</div>
        <div class="form-wrapper">
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
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" id="remember" value="1">
                            记住我
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-block ">登录</button>
                </div>
                <input type="hidden" name="csrf_token" value="{{$_token}}">
            </form>
        </div>
    </div>
</div>

<script src="/vendor/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="/vendor/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
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
