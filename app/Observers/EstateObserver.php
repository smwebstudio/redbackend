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
//       dd(request()->input('estateDocuments'), $estate);
    }


    /**
     * Handle the Estate "created" event.
     */
    public function created(Estate $estate): void
    {
        $code = 0;
        if($estate->contract_type !=null){
            $code .= $estate->contract_type->id;
        }else{
            $code .= "-";
        }
        if($estate->estate_type !=null){
            $code .= $estate->estate_type->id;
        }else{
            $code .= "-";
        }


        $estate->code = $code.'-'.$estate->id;

        $estate->save();
    }

    /**
     * Handle the Estate "updating" event.
     */
    public function updating(Estate $estate): void
    {
        $code = '';
        if($estate->contract_type !=null){
            $code .= $estate->contract_type->id === 1 ? 0 : 1 ;
        }else{
            $code .= "-";
        }
        if($estate->estate_type !=null){
            $code .= $estate->estate_type->id;
        }else{
            $code .= "-";
        }

        if ($estate->room_count != null && $estate->room_count != 0) {
            $code .= $estate->room_count;
        } else {
            $code .= "-";
        }


        $estate->code = $code.'-'.$estate->id;
    }

    /**
     * Handle the Estate "updated" event.
     */
    public function updated(Estate $estate): void
    {
        $code = '';
        if($estate->contract_type !=null){
            $code .= $estate->contract_type->id === 1 ? 0 : 1 ;
        }else{
            $code .= "-";
        }
        if($estate->estate_type !=null){
            $code .= $estate->estate_type->id;
        }else{
            $code .= "-";
        }

        if ($estate->room_count != null && $estate->room_count != 0) {
            $code .= $estate->room_count;
        } else {
            $code .= "-";
        }


        $estate->code = $code.'-'.$estate->id;
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




//        $finalPath = "/estate/photos/records/$estate->id";
//        if (request()->has('files')) {
//            $files = request()->input('files');
//
//            if ($files) {
//                event(new EstateDocumentUploaded(
//                        finalPath: $finalPath,
//                        files: $files,
//                        model: $estate->withoutGlobalScopes()->where('id', $estate->id)->first(),
//                        fileService: $this->fileService
//                    )
//                );
//            }
//        }


    }
}
