@if ($crud->hasAccess('update'))
    @if (!$crud->model->translationEnabled())
        {{-- Single edit button --}}

        @if($entry->estate_type_id)
            @if($entry->estate_type_id == 1)
                <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit?estateType='.$entry->estate_type_id) }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->estate_type_id == 2)
                <a href="{{ url('/admin/house/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->estate_type_id == 3)
                <a href="{{ url('/admin/commercial/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->estate_type_id == 4)
                <a href="{{ url('/admin/land/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
        @elseif($entry->contact_type_id)
            @if($entry->contact_type_id == 1)
                <a href="{{ url('/admin/seller/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->contact_type_id == 2)
                <a href="{{ url('/admin/owner/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->contact_type_id == 3)
                <a href="{{ url('/admin/agent/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->contact_type_id == 4)
                <a href="{{ url('/admin/buyer/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
            @if($entry->contact_type_id == 5)
                <a href="{{ url('/admin/renter/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
            @endif
        @else
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}"
               class="btn btn-sm btn-link"><i class="la la-edit"></i></a>
        @endif
    @else

        {{-- Edit button group --}}
        <div class="btn-group">
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link pr-0"><i
                    class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</a>
            <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                    <a class="dropdown-item"
                       href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?_locale={{ $key }}">{{ $locale }}</a>
                @endforeach
            </ul>
        </div>

    @endif
@endif
