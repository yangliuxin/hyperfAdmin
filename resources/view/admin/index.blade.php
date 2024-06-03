@extends('admin.layout.default')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                仪表盘
                <small>数据统计</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                @foreach($statistics as $key => $val)
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon {{$val['class']}}"><i class="ion {{$val['icon']}}"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{$val['title']}}</span>
                            <span class="info-box-number">{{$val['data']}}</span>
                        </div>
                    </div>
                </div>
                @if($key % 2 == 0)
                <div class="clearfix visible-sm-block"></div>
                @endif
                @endforeach
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">热门访问网址</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>URI</th>
                                        <th>访问次数</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($hotUriList as $key => $val)
                                        <tr>
                                            <td>{{ $val['uri'] }}</td>
                                            <td><span class="label label-success">{{ $val['num'] }}</span></td>
                                        </tr>
                                    @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">访问地区统计</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <canvas id="pieChart" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">访问统计折线图</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">
                            <div class="chart" id="line-chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/dist/js/adminlte.min.js"></script>
    <script src="/vendor/js/app.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/chart.js/Chart.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/morris.js/morris.min.js"></script>
    <script src="/vendor/AdminLTE-2.4.18/bower_components/raphael/raphael.min.js"></script>
    <script src="/vendor/echarts/echarts.min.js"></script>
    <script>
        var chartJson = '{!! $pieChartData !!}';
        var chartData = JSON.parse(chartJson);
        var myChart_1717071903 = echarts.init(document.getElementById('pieChart'),null,{renderer: 'canvas'});
        myChart_1717071903.setOption({
            title: {
                text: chartJson.title,
                x: 'left'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                type: 'scroll',
                orient: 'vertical',
                right: 'left',
                data: chartData.legends
            },
            series: [
                {
                    name: chartData.seriesName,
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '50%'],
                    data: chartData.seriesData,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        });


        var chartJson = '{!! $lineChartData !!}';
        var chartData = JSON.parse(chartJson);
        var myChart = echarts.init(document.getElementById('line-chart'),null,{renderer: 'canvas'});
        myChart.setOption({
            color: [
                '#ff7f50', '#87cefa', '#ff00ff', '#32cd32', '#7b68ee',
                '#0A0A0A', '#b8860b', '#cd5c5c', '#ffd700', '#00fa9a'
            ],
            title: {
                show: false,
                text: chartData.title
            },
            legend: {
                left: 'left',
                data: chartData.legend.data,
                selected: chartData.legend.selected
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross'
                }
            },
            toolbox: {
                show: true,
                feature: {
                    magicType : {show: true, type: ['line','bar']},
                }
            },
            calculable: true,
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: chartData.xAxisData
            },
            yAxis: [
                {
                    name: chartData.yAxisName,
                    type: 'value',
                    position: 'left'
                }
            ],
            series: chartData.seriesData,
            dataZoom:{
                show:true
            }
        });

    </script>
@endsection