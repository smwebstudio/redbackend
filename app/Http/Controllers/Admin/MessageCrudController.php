<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class MessageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MessageCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Message::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/message');
        CRUD::setEntityNameStrings('նամակ', 'նամակներ');
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
            'label' => "Կոդ",
        ]);

        CRUD::addColumn([
            'name' => 'recipient',
            'type' => "relationship",
            'attribute' => "full_name",
            'label' => "Հասցեատեր",
        ]);

        CRUD::addColumn([
            'name' => 'sender_name',
            'type' => "text",
            'label' => "Ուղարկող",
        ]);

        CRUD::addColumn([
            'name' => 'sender_email',
            'type' => "text",
            'label' => "Էլ. հասցե",
        ]);

        CRUD::addColumn([
            'name' => 'sender_phone',
            'type' => "text",
            'label' => "Հեռ.",
        ]);

        CRUD::addColumn([
            'name' => 'sent_on',
            'type' => "text",
            'label' => "Ուղղարկման ամսաթիվ",
        ]);


//        CRUD::column('message_type_id');
//        CRUD::column('feedback_type_id');
//        CRUD::column('service_id');
//        CRUD::column('overall_rating');
//        CRUD::column('offering_price');
//        CRUD::column('offering_currency_id');
//        CRUD::column('message_text');
//        CRUD::column('is_read');
//        CRUD::column('is_verified');
//        CRUD::column('sent_on');
//        CRUD::column('ip_address');

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
        CRUD::setValidation(MessageRequest::class);

        CRUD::field('recipient_id');
        CRUD::field('estate_id');
        CRUD::field('sender_name');
        CRUD::field('sender_email');
        CRUD::field('sender_phone');
        CRUD::field('message_type_id');
        CRUD::field('feedback_type_id');
        CRUD::field('service_id');
        CRUD::field('overall_rating');
        CRUD::field('offering_price');
        CRUD::field('offering_currency_id');
        CRUD::field('message_text');
        CRUD::field('is_read');
        CRUD::field('is_verified');
        CRUD::field('sent_on');
        CRUD::field('ip_address');

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
        $this->authorize('create', Message::class);
    }
}
