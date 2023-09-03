@if(count($columns))
    <div class="table table-striped m-0 p-0 ">
        <div style=" display: flex;
    flex-direction: row;
    flex-shrink: 0;
    flex-flow: row wrap; ">
            @foreach($columns as $column)
                <div class="{!! isset($column['className']) ? $column['className'] : '' !!}" style="display: flex; flex-direction: row; gap: 16px;">
                    <div  class="border-top-0 basis-3/6 grow-1 ">
                        <strong>{!! $column['label'] !!}</strong>
                    </div>
                    <div class="border-top-0 basis-3/6 grow-1">
                        @php
                            // create a list of paths to column blade views
                            // including the configured view_namespaces
                            $columnPaths = array_map(function($item) use ($column) {
                                return $item.'.'.$column['type'];
                            }, \Backpack\CRUD\ViewNamespaces::getFor('columns'));

                            // but always fall back to the stock 'text' column
                            // if a view doesn't exist
                            if (!in_array('crud::columns.text', $columnPaths)) {
                                $columnPaths[] = 'crud::columns.text';
                            }
                        @endphp
                        @includeFirst($columnPaths)
                    </div>
                </div>
            @endforeach
            @if($crud->buttons()->where('stack', 'line')->count() && ($displayActionsColumn ?? true))
                <div>
                    <div>
                        <strong>{{ trans('backpack::crud.actions') }}</strong>
                    </div>
                    <div>
                        @include('crud::inc.button_stack', ['stack' => 'line'])
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
