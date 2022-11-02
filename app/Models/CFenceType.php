<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CFenceType
 * 
 * @property int $id
 * @property bool|null $is_deleted
 * @property Carbon|null $last_modified_on
 * @property int|null $version
 * @property string|null $sort_id
 * @property string|null $name_arm
 * @property string|null $name_eng
 * @property string|null $name_ru
 * @property string|null $name_ar
 * @property int|null $last_modified_by
 * @property string|null $comment
 * @property int|null $created_by
 * @property Carbon|null $created_on
 * 
 * @property Collection|Announcement[] $announcements
 * @property Collection|Estate[] $estates
 *
 * @package App\Models
 */
class CFenceType extends Model
{
	protected $table = 'c_fence_type';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'is_deleted' => 'bool',
		'version' => 'int',
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

	public function announcements()
	{
		return $this->hasMany(Announcement::class, 'fence_type_id');
	}

	public function estates()
	{
		return $this->hasMany(Estate::class, 'fence_type_id');
	}
}
