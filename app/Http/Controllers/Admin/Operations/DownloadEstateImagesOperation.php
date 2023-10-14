<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

trait DownloadEstateImagesOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDownloadEstateImagesRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/download-estate-images', [
            'as'        => $routeName.'.downloadEstateImages',
            'uses'      => $controller.'@downloadEstateImages',
            'operation' => 'downloadEstateImages',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDownloadEstateImagesDefaults()
    {

        CRUD::allowAccess('downloadEstateImages');

        CRUD::operation('downloadEstateImages', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('show', function () {
             CRUD::addButton('top', 'download_estate_images', 'view', 'crud::buttons.download_estate_images');
             CRUD::addButton('line', 'download_estate_images', 'view', 'crud::buttons.download_estate_images');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function downloadEstateImages()
    {


        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Download Estate Images '.$this->crud->entity_name;

        // load the view
        return view('crud::operations.download_estate_images', $this->data);
    }
}
