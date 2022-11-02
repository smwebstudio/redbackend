<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * 
 * @property int $id
 * @property string|null $code
 * @property int|null $estate_type_id
 * @property int|null $project_type_id
 * @property string|null $name_arm
 * @property string|null $name_eng
 * @property string|null $name_ru
 * @property string|null $name_ar
 * @property string|null $description_arm
 * @property string|null $description_eng
 * @property string|null $description_ru
 * @property string|null $description_ar
 * @property string|null $main_photo_name
 * @property string|null $main_photo_path
 * @property string|null $main_photo_thumb_path
 * @property Carbon|null $created_on
 * @property int|null $created_by
 * @property Carbon|null $updated_on
 * @property int|null $updated_by
 *
 * @package App\Models
 */
class Project extends Model
{
	protected $table = 'project';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'estate_type_id' => 'int',
		'project_type_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'created_on',
		'updated_on'
	];

	protected $fillable = [
		'code',
		'estate_type_id',
		'project_type_id',
		'name_arm',
		'name_eng',
		'name_ru',
		'name_ar',
		'description_arm',
		'description_eng',
		'description_ru',
		'description_ar',
		'main_photo_name',
		'main_photo_path',
		'main_photo_thumb_path',
		'created_on',
		'created_by',
		'updated_on',
		'updated_by'
	];
}
