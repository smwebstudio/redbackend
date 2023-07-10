<?php

namespace App\Observers;

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
     * Handle the Estate "created" event.
     */
    public function created(Estate $estate): void
    {
        //
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
        $patientId = $estate->patient_id;
        $finalPath = "/patient/$patientId/records/$estate->id";
        if (request()->has('files')) {
            $files = request()->input('files');

            if ($files) {
                event(new RecordFileUploaded(
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
