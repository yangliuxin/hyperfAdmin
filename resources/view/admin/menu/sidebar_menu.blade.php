@if(count($menuList) > 0)
@foreach($menuList as $key => $val)
    @if(!$val)
        @break
    @endif
    <li class="dd-item" data-id="{{ $val['id'] }}">
        <div class="dd-handle">
            @if($val['type'] == 1)
                <i class="fa {{$val['icon']}}"></i>
            @endif
            @if($val['type'] == 2)
                <i class="fa fa-file" ></i>
            @endif
            &nbsp;
            <strong>{{$val['title']}}</strong>
            <span class="pull-right dd-nodrag">
              <a href="/admin/menu/edit/{{$val['id']}}"><i class="fa fa-edit"></i></a>
              <a href="javascript:void(0);" data-id="{{$val['id']}}" class="tree_branch_delete"><i class="fa fa-trash"></i></a>
            </span>
        </div>
        @if(isset($val['children']))
            @if(count($val['children']) > 0)
            <ol class="dd-list">
                @includeIf('admin.menu.sidebar_menu', ['menuList' => $val['children']])
            </ol>
            @endif
        @endif
    </li>
@endforeach
@endif