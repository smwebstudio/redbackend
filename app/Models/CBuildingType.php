<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CBuildingType extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_deleted',
        'last_modified_on',
        'version',
        'sort_id',
        'name_arm',
        'name_eng',
        'name_ru',
        'name_ar',
        'last_modified_by',
        'comment',
        'created_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_deleted' => 'integer',
        'last_modified_on' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];
}
