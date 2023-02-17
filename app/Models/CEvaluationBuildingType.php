<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ApiMultiLanguage;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CEvaluationBuildingType
 *
 * @property int $id
 * @property string|null $name_arm
 * @property string|null $name_eng
 * @property string|null $name_ru
 * @property string|null $name_ar
 *
 * @package App\Models
 */
class CEvaluationBuildingType extends Model
{
	protected $table = 'c_evaluation_building_type';
	public $incrementing = false;
	public $timestamps = false;

    use ApiMultiLanguage;
    protected array $multi_lang = [
        'name',
    ];

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'name_arm',
		'name_eng',
		'name_ru',
		'name_ar'
	];
}
