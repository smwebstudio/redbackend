@if ($crud->hasAccess('show'))
    @if (!$crud->model->translationEnabled())


        @if($entry->estate_status_id != 8)
            @if($entry->estate_type_id == 1 || $entry->estate_type_id == 2 || $entry->estate_type_id == 3 || $entry->estate_type_id == 4)
                <a href="{{ url('/admin/estate/'.$entry->getKey().'/edit?action=selled#lracvoucich') }}"
                   class="btn btn-sm btn-link"><i class="la la-check-square"></i></a>
            @endif
            @if($entry->estate_type_id == 2)
                <a href="{{ url('/admin/house/'.$entry->getKey().'/edit?action=selled') }}"
                   class="btn btn-sm btn-link"><i class="la la-check-square"></i></a>
            @endif
            @if($entry->estate_type_id == 3)
                <a href="{{ url('/admin/commercial/'.$entry->getKey().'/edit?action=selled') }}"
                   class="btn btn-sm btn-link"><i class="la la-check-square"></i></a>
            @endif
            @if($entry->estate_type_id == 4)
                <a href="{{ url('/admin/land/'.$entry->getKey().'/edit?action=selled') }}"
                   class="btn btn-sm btn-link"><i class="la la-check-square"></i></a>
            @endif
            @if($entry->estate_type_id == 5)
                <a href="{{ url('/admin/townhouse/'.$entry->getKey().'/edit?action=selled') }}"
                   class="btn btn-sm btn-link"><i class="la la-check-square"></i></a>
            @endif
        @endif



    @else

        {{-- show button group --}}
        <div class="btn-group">
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit?action=selled') }}" class="btn btn-sm btn-link pr-0"><i class="la la-check-square"></i> {{ trans('backpack::crud.preview') }}</a>
            <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header">{{ trans('backpack::crud.preview') }}:</li>
                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                    <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit?action=selled') }}?_locale={{ $key }}">{{ $locale }}</a>
                @endforeach
            </ul>
        </div>

    @endif
@endif
