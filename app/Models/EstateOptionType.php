<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EstateOptionType
 *
 * @package App\Models
 */
class EstateOptionType extends Model
{

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'name_arm' => 'string',
    ];



    protected $fillable = [
        'name_arm',
        'name_eng',
        'name_ru',
        'estate_type',
    ];


}
