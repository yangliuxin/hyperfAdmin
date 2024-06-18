
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>后台管理</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/vendor/AdminLTE/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE-2.4.18/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE-2.4.18/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE-2.4.18/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/vendor/AdminLTE/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="/vendor/nestable/nestable.css">
    <link rel="stylesheet" href="/vendor/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/vendor/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css">
    <link rel="stylesheet" href="/vendor/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="/vendor/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="/vendor/jstree/themes/default/style.min.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->
</head>
<body class="hold-transition skin-purple-light fixed sidebar-mini">
<div class="wrapper">
    <div>
        @include('admin.widgets.header')
    </div>
    @include('admin.widgets.sidebar')
    @yield('content')

    @include('admin.widgets.footer')
</div>
</body>
</html>
