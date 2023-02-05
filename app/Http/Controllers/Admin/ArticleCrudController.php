<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('article', 'articles');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('article_type_id');
        CRUD::column('title_arm');
        CRUD::column('title_eng');
        CRUD::column('title_ru');
        CRUD::column('title_ar');
        CRUD::column('content_arm');
        CRUD::column('content_eng');
        CRUD::column('content_ru');
        CRUD::column('content_ar');
        CRUD::column('created_by');
        CRUD::column('created_on');
        CRUD::column('last_modified_by');
        CRUD::column('last_modified_on');
        CRUD::column('main_image_file_name');
        CRUD::column('main_image_file_path');
        CRUD::column('main_image_file_path_thumb');
        CRUD::column('is_published');
        CRUD::column('is_approved');
        CRUD::column('view_count');
        CRUD::column('metatitle_eng');
        CRUD::column('metatitle_arm');
        CRUD::column('metatitle_ru');
        CRUD::column('metatitle_ar');

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
        CRUD::setValidation(ArticleRequest::class);

        CRUD::field('article_type_id');
        CRUD::field('title_arm');
        CRUD::field('title_eng');
        CRUD::field('title_ru');
        CRUD::field('title_ar');
        CRUD::field('content_arm');
        CRUD::field('content_eng');
        CRUD::field('content_ru');
        CRUD::field('content_ar');
        CRUD::field('created_by');
        CRUD::field('created_on');
        CRUD::field('last_modified_by');
        CRUD::field('last_modified_on');
        CRUD::field('main_image_file_name');
        CRUD::field('main_image_file_path');
        CRUD::field('main_image_file_path_thumb');
        CRUD::field('is_published');
        CRUD::field('is_approved');
        CRUD::field('view_count');
        CRUD::field('metatitle_eng');
        CRUD::field('metatitle_arm');
        CRUD::field('metatitle_ru');
        CRUD::field('metatitle_ar');

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
