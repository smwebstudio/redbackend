<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EstateRequest;
use App\Models\Contact;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EstateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EstateCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    public function fetchContact()
    {
        return $this->fetch([
        'model' => Contact::class,
        'searchable_attributes' => ['name_arm', 'last_name_arm'],
        'paginate' => 10, // items to show per page
        'searchOperator' => 'LIKE',
        'query' => function($model) {
            return $model->where('contact_type_id', '=', 3);
        }
    ]);
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Estate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estate');
        CRUD::setEntityNameStrings('estate', 'estates');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => "text",
            'label' => "ID",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'code',
            'type' => "text",
            'label' => "Code",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'c_contract_type',
            'type' => "relationship",
            'label' => "Contract type",
            'attribute' => "name_arm",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'area_total',
            'type' => "text",
            'label' => "Area",
            'escaped' => false,
            'suffix' => ' m<sup>2</sup>',
        ]);

        CRUD::addColumn([
            'name' => 'contact',
            'type' => "relationship",
            'attribute' => "full_contact",
            'label' => "Seller",
            'limit' => 150,
        ]);

        CRUD::addColumn([
            'name' => 'c_location_country',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Country",
        ]);
        CRUD::addColumn([
            'name' => 'c_location_province',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Province",
        ]);

        CRUD::addColumn([
            'name' => 'created_on',
            'type' => "date",
            'label' => "Created at",
        ]);

        CRUD::addColumn([
            'name' => 'last_modified_on',
            'type' => "date",
            'label' => "Updated at",
        ]);

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EstateRequest::class);

        CRUD::addField([
            'name' => 'c_contract_type',
            'type' => "relationship",
            'label' => "Contract type",
        ]);

        CRUD::addField([
            'name' => 'contact',
            'type' => "relationship",
            'label' => "Contact",
            'ajax' => true,
        ]);

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
