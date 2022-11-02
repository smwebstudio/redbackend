{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@includeWhen(class_exists(\Backpack\DevTools\DevToolsServiceProvider::class), 'backpack.devtools::buttons.sidebar_item')

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-building-type') }}"><i class="nav-icon la la-question"></i> C building types</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-question"></i> Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('model-has-permission') }}"><i class="nav-icon la la-question"></i> Model has permissions</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('model-has-role') }}"><i class="nav-icon la la-question"></i> Model has roles</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-question"></i> Permissions</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-question"></i> Roles</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role-has-permission') }}"><i class="nav-icon la la-question"></i> Role has permissions</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-building-project-type') }}"><i class="nav-icon la la-question"></i> C building project types</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('estate') }}"><i class="nav-icon la la-question"></i> Estates</a></li>