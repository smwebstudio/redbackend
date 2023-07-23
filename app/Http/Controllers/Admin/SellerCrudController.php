<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Traits\Controllers\AddContactListColumns;
use App\Traits\Controllers\HasContactFilters;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class ContactCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SellerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use AuthorizesRequests;
    use HasContactFilters;
    use AddContactListColumns;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Contact::class);
        $this->crud->addClause('where', 'contact_type_id', '!=', 6);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/seller');
        CRUD::setEntityNameStrings('կոնտակտ', 'կոնտակտներ');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->addListColumns();
    }


    protected function setupShowOperation()
    {

        $this->authorize('create', Contact::class);
        CRUD::addColumn([
            'name' => 'contactType',
            'type' => "relationship",
            'label' => "Կոնտակտի տեսակը",
            'attribute' => "name_arm",
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
        CRUD::setValidation(ContactRequest::class);
        $this->crud->setCreateContentClass('col-md-8');
//        $this->authorize('create', Contact::class);


        CRUD::addField([
            'name' => 'contact_type',
            'type' => "relationship",
            'label' => "Գույքի տեսակ",
            'default' => 1,
            'attributes' => [
                'readonly' => 'readonly',
            ],
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3 d-none'
            ],
        ]);

        CRUD::addField([
            'name' => 'is_seller',
            'type' => "switch",
            'default' => 1,
            'wrapper' => [
                'class' => 'form-group col-md-3 d-none'
            ],
        ]);



        CRUD::addField([
            'name' => 'name_arm',
            'type' => "text",
            'label' => "Անուն",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'last_name_arm',
            'type' => "text",
            'label' => "Ազգանուն",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);



        CRUD::addField([
            'name' => 'email',
            'type' => "text",
            'label' => "Էլ. հասցե",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_mobile_1',
            'type' => "text",
            'label' => "Բջջ. հեռ. 1",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_mobile_2',
            'type' => "text",
            'label' => "Բջջ. հեռ. 2",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_mobile_3',
            'type' => "text",
            'label' => "Բջջ. հեռ. 3",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_mobile_4',
            'type' => "text",
            'label' => "Բջջ. հեռ. 4",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_office',
            'type' => "text",
            'label' => "Գրասենյակի հեռ.",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_home',
            'type' => "text",
            'label' => "Տան հեռ.",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'viber',
            'type' => "text",
            'label' => "Viber",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'whatsapp',
            'type' => "text",
            'label' => "WhatsApp",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'comment_arm',
            'type' => "textarea",
            'label' => "Մեկնաբանություն",
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


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
