<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ApiMultiLanguage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CEvaluationInteriorDesign
 *
 * @property int $id
 * @property string|null $sort_id
 * @property string|null $name_arm
 * @property string|null $name_eng
 * @property string|null $name_ru
 * @property string|null $name_ar
 * @property float|null $coefficient
 * @property Carbon|null $last_modified_on
 * @property int|null $last_modified_by
 * @property int|null $created_by
 * @property Carbon|null $created_on
 *
 * @package App\Models
 */
class CEvaluationInteriorDesign extends Model
{
	protected $table = 'c_evaluation_interior_design';
	public $incrementing = false;
	public $timestamps = false;

    use ApiMultiLanguage;
    protected array $multi_lang = [
        'name',
    ];

	protected $casts = [
		'id' => 'int',
		'coefficient' => 'float',
		'last_modified_by' => 'int',
		'created_by' => 'int'
	];

	protected $dates = [
		'last_modified_on',
		'created_on'
	];

	protected $fillable = [
		'sort_id',
		'name_arm',
		'name_eng',
		'name_ru',
		'name_ar',
		'coefficient',
		'last_modified_on',
		'last_modified_by',
		'created_by',
		'created_on'
	];
}
