<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CBuildingProjectType
 *
 * @property int $id
 * @property int|null $is_deleted
 * @property Carbon|null $last_modified_on
 * @property int|null $version
 * @property int|null $sort_id
 * @property string|null $name_arm
 * @property string|null $name_eng
 * @property string|null $name_ru
 * @property string|null $name_ar
 * @property int|null $last_modified_by
 * @property string|null $comment
 * @property int|null $created_by
 * @property Carbon|null $created_on
 *
 * @package App\Models
 */
class CBuildingProjectType extends Model
{
    use CrudTrait;
	protected $table = 'c_building_project_type';
	public $incrementing = false;
	public $timestamps = false;
    protected string $identifiableAttribute = 'name_arm';

	protected $casts = [
		'id' => 'int',
		'is_deleted' => 'int',
		'version' => 'int',
		'sort_id' => 'int',
		'last_modified_by' => 'int',
		'created_by' => 'int'
	];

	protected $dates = [
		'last_modified_on',
		'created_on'
	];

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
		'created_on'
	];
}
