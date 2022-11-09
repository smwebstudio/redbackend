<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CLocationCountryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CLocationCountryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CLocationCountryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CLocationCountry::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/c-location-country');
        CRUD::setEntityNameStrings('Erkir', 'Erkrner');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('is_deleted');
        CRUD::column('last_modified_on');
        CRUD::column('version');
        CRUD::column('sort_id');
        CRUD::column('name_arm');
        CRUD::column('name_eng');
        CRUD::column('name_ru');
        CRUD::column('name_ar');
        CRUD::column('last_modified_by');
        CRUD::column('comment');
        CRUD::column('created_by');
        CRUD::column('created_on');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CLocationCountryRequest::class);

        CRUD::field('name_arm');
        CRUD::field('name_eng');
        CRUD::field('name_ru');
        CRUD::field('name_ar');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
