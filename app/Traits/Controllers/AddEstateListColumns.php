<?php

namespace App\Traits\Controllers;

use App\Models\Contact;
use App\Models\RealtorUser;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait AddEstateListColumns
{
    private function addListColumns()  {


//        CRUD::addColumn([
//            'name' => 'id',
//            'label' => "ID",
//            'searchLogic' => function ($query, $column, $searchTerm) {
//                $query->orWhere('id', 'like', '%' . $searchTerm . '%')->orWhere('code', 'like', '%' . $searchTerm . '%');
//            },
//        ]);


        CRUD::addColumn([
            'name' => 'estate_status_id',
            'type' => "custom_html",
            'label' => "",
            'limit' => 100,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('id', 'like', '%' . $searchTerm . '%')->orWhere('code', 'like', '%' . $searchTerm . '%');
            },
            'value' => function ($entry) {

                $statusIcon = '';

                if ($entry->estate_status_id === 1) {
                    $statusIcon = '<i class="las la-file" style="font-size: 24px; color: #C00"  title="Սևագիր"></i>';
                }

                if ($entry->estate_status_id === 2) {
                    $statusIcon = '<i class="las la-file-alt" style="font-size: 24px; color: #939309"  title="Թերի Լրացված"></i>';
                }

                if ($entry->estate_status_id === 3) {
                    $statusIcon = '<i class="las la-camera-retro" style="font-size: 24px; color: #00a2d6"  title="Տեղազնված"></i>';
                }

                if ($entry->estate_status_id === 4) {
                    $statusIcon = '<i class="las la-check-square" style="font-size: 24px; color: #066c3c"  title="Հաստատված"></i>';
                }

                if ($entry->estate_status_id === 6) {
                    $statusIcon = '<i class="las la-tag" style="font-size: 24px; color: #066c3c" title="Վարձակալված"></i>';
                }


                if ($entry->estate_status_id === 7) {
                    $statusIcon = '<i class="las la-calendar-check" style="font-size: 24px; color: #9369aa" title="Վաճառված"></i>';
                }

                if ($entry->estate_status_id === 8) {
                    $statusIcon = '<i class="las la-file-download" style="font-size: 24px; color: #222f3e" title="Արխիվացված"></i>';
                }

                if($entry->is_urgent == 1) {
                    $statusIcon .= '<i class="las la-bolt" style="font-size: 24px; color: #fd9002" title="Շտապ"></i>';
                }


                return $statusIcon;

            },
            'wrapper' => [
                'element' => 'span',
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('article/' . $related_key . '/show');
                },
            ],
        ]);

        CRUD::addColumn([
            'name' => 'full_code',
            'type' => "markdown",
            'value' => function ($entry) {
                return '<div style="text-align: center"><a href="/admin/estate/' . $entry->id . '/show">' . $entry->full_code . '</a></div>';
            },
            'label' => "Կոդ",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'full_address',
            'type' => "text",
            'label' => "Հասցե",
            'limit' => 500,
        ]);

        CRUD::addColumn([
            'name' => 'full_price_with_change',
            'type' => "markdown",
            'label' => "Գին",
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('price', $columnDirection);
            }
        ]);

        CRUD::addColumn([
            'name' => 'area_total',
            'type' => "text",
            'label' => "Մակերես",
            'escaped' => false,
            'suffix' => ' m<sup>2</sup>',
        ]);

        CRUD::addColumn([
            'name' => 'price_per_square',
            'type' => "text",
            'label' => "$/S",
            'suffix' => session('currency') ?  ' '.session('currency') : ' AMD',
        ]);

        CRUD::addColumn([
            'name' => 'contact',
            'type' => "relationship",
            'attribute' => "full_contact",
            'label' => "Վաճառող",
            'limit' => 150,
        ]);


        CRUD::addColumn([
            'name' => 'main_image_file_path_thumb', // The db column name
            'label' => 'Նկար', // Table column heading
            'type' => 'image',
            'prefix' => '/estate/photos/',
            'disk' => 'S3Public',
            'height' => '70px',
            'width' => '90px',
        ]);

        CRUD::addColumn([
            'name' => 'created_at', // The db column name
            'label' => 'Ստեղծված', // Table column heading
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'updated_at', // The db column name
            'label' => 'Թարմացված', // Table column heading
            'type' => 'text',
        ]);
    }
}
