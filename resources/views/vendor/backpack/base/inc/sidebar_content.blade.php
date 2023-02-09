{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@includeWhen(class_exists(\Backpack\DevTools\DevToolsServiceProvider::class), 'backpack.devtools::buttons.sidebar_item')

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('estate') }}"><i class="nav-icon la la-home"></i> Estates</a></li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-bars"></i> Locations</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-location-country') }}"><i class="nav-icon la la-cog"></i> Countries</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-location-province') }}"><i class="nav-icon la la-cog"></i> Provinces</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-location-street') }}"><i class="nav-icon la la-cog"></i> Streets</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-location-community') }}"><i class="nav-icon la la-cog"></i> Communities</a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-bars"></i> Estate settings</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-building-type') }}"><i class="nav-icon la la-cog"></i> Building types</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-building-project-type') }}"><i class="nav-icon la la-cog"></i> Building project types</a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="las la-bars"></i> User management</a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-cog"></i> Users</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-cog"></i> Permissions</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-cog"></i> Roles</a></li>
    </ul>
</li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('contact') }}"><i class="nav-icon la la-question"></i> Contacts</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-contact-type') }}"><i class="nav-icon la la-question"></i> C contact types</a></li>


<li class="nav-item"><a class="nav-link" href="{{ backpack_url('article') }}"><i class="nav-icon la la-question"></i> Articles</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('c-article-type') }}"><i class="nav-icon la la-question"></i> Article types</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('estate-option-type') }}"><i class="nav-icon la la-question"></i> Estate option types</a></li>