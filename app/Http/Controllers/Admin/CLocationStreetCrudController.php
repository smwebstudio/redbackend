<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CLocationStreetRequest;
use App\Models\Message;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class CLocationStreetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CLocationStreetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use AuthorizesRequests;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CLocationStreet::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/c-location-street');
        CRUD::setEntityNameStrings('փողոց', 'փողոցներ');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('name_arm');
        CRUD::column('name_eng');
        CRUD::column('name_ru');
        CRUD::column('created_on');
        CRUD::column('last_modified_on');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->authorize('create', Message::class);
        CRUD::setValidation(CLocationStreetRequest::class);

        CRUD::field('is_deleted');
        CRUD::field('last_modified_on');
        CRUD::field('version');
        CRUD::field('sort_id');
        CRUD::field('location');

        CRUD::addField([
            'name' => 'parent_id',
            'type' => "relationship",
            'label' => "Contact",
            'ajax' => true,
        ]);

        CRUD::field('name_arm');
        CRUD::field('name_eng');
        CRUD::field('name_ru');
        CRUD::field('name_ar');
        CRUD::field('last_modified_by');
        CRUD::field('comment');
        CRUD::field('created_by');
        CRUD::field('created_on');
        CRUD::field('community_id');
        CRUD::field('city_id');
        CRUD::field('parent_is_community');

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

    protected function setupShowOperation()
    {
        $this->setupCreateOperation();
    }
}
