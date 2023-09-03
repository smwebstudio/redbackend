<?php

namespace App\Traits\Controllers;

use App\Models\Contact;
use App\Models\RealtorUser;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait AddContactShowColumns
{
    private function addShowColumns()  {

        $this->crud->addButton('top', 'contact_create_buttons_set', 'view', 'crud::buttons.contact_create_buttons_set');
        $this->crud->removeButton('create');
        CRUD::enableExportButtons();

        CRUD::addColumn([
            'name' => 'id',
            'type' => "text",
            'label' => "Կոդ",
            'className' => 'col-md-7',
            'tab' => 'Հիմնական',
            'orderable'  => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('contact_type_id', $columnDirection);
            }
        ]);


        CRUD::addColumn([
            'name' => 'full_contact',
            'type' => "text",
            'label' => "Անուն",
            'attribute' => "full_contact",
            'limit' => 100,
            'orderable'  => true,
            'className' => 'col-md-7',
            'tab' => 'Հիմնական',
        ]);


        CRUD::addColumn([
            'name' => 'contact_type',
            'type' => "relationship",
            'label' => "Կոնտակտի տեսակը",
            'attribute' => "name_arm",
            'limit' => 100,'className' => 'col-md-7',
            'tab' => 'Հիմնական',
            'orderable'  => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('contact_type_id', $columnDirection);
            }
        ]);

        CRUD::addColumn([
            'name' => 'phone_mobile_1',
            'type' => "text",
            'label' => "Հեռախոս",
            'className' => 'col-md-7',
            'tab' => 'Հիմնական',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'type' => "text",
            'label' => "Էլ․ հասցե",
            'className' => 'col-md-7',
            'tab' => 'Հիմնական',
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'type' => "text",
            'label' => "Ստեղծված",
            'className' => 'col-md-7',
            'tab' => 'Հիմնական',
        ]);

        CRUD::addColumn([
            'name' => 'updated_at',
            'type' => "text",
            'label' => "Թարմացված",
            'className' => 'col-md-7',
            'tab' => 'Հիմնական',
        ]);

    }
}
