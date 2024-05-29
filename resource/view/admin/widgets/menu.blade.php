@foreach($menuDataList as $key => $val)
    <li @if(isset($val['children']) && count($val['children']) > 0)  class="treeview" @endif>
        <a href="{{$val['uri']}}">
            <i class="fa {{$val['icon']}}"></i>
            <span>{{$val['title']}}</span>
            @if(isset($val['children']) && count($val['children']) > 0)
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            @endif
        </a>
        @if(isset($val['children']) && count($val['children']) > 0)
            <ul class="treeview-menu">
                @includeIf("admin.widgets.menu", ['menuDataList' => $val['children']])
            </ul>
        @endif
    </li>
@endforeach