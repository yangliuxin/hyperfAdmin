@foreach($data as $key => $val)
<option value="{{$val['id']}}">@if($val['level'] > 1)
            @for($i = 1; $i < $val['level']; $i++)
            &nbsp;&nbsp;
            @endfor
        @endif┝  {{$val['title']}}</option>
    @if(isset($val['children']) && count($val['children']) > 0)
        @includeIf("admin.menu.menu_options", ['data' => $val['children']])
    @endif
@endforeach
