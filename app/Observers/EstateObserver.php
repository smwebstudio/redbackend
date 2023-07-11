<?php

namespace App\Observers;

use App\Events\EstateDocumentUploaded;
use App\Models\Estate;
use App\Services\FileService;

class EstateObserver
{

    private FileService $fileService;


    /**
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Handle the Estate "creating" event.
     */
    public function creating(Estate $estate): void
    {
       dd(request()->input('estateDocuments'), $estate);
    }


    /**
     * Handle the Estate "created" event.
     */
    public function created(Estate $estate): void
    {
        $estate->name_arm = 'name arm';

        $estate->save();
    }

    /**
     * Handle the Estate "updated" event.
     */
    public function updated(Estate $estate): void
    {
        //
    }

    /**
     * Handle the Estate "deleted" event.
     */
    public function deleted(Estate $estate): void
    {
        //
    }

    /**
     * Handle the Estate "restored" event.
     */
    public function restored(Estate $estate): void
    {
        //
    }

    /**
     * Handle the Estate "force deleted" event.
     */
    public function forceDeleted(Estate $estate): void
    {
        //
    }

    public function saved(Estate $estate)
    {
        $finalPath = "/estate/photos/records/$estate->id";
        if (request()->has('files')) {
            $files = request()->input('files');

            if ($files) {
                event(new EstateDocumentUploaded(
                        finalPath: $finalPath,
                        files: $files,
                        model: $estate->withoutGlobalScopes()->where('id', $estate->id)->first(),
                        fileService: $this->fileService
                    )
                );
            }
        }


    }
}
