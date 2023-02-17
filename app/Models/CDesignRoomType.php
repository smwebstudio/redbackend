<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ApiMultiLanguage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CDesignRoomType
 *
 * @property int $id
 * @property int|null $parent_id
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
 * @property CDesignRoom|null $c_design_room
 * @property Collection|Design[] $designs
 *
 * @package App\Models
 */
class CDesignRoomType extends Model
{
	protected $table = 'c_design_room_type';
	public $incrementing = false;
	public $timestamps = false;

    use ApiMultiLanguage;
    protected array $multi_lang = [
        'name',
    ];

	protected $casts = [
		'id' => 'int',
		'parent_id' => 'int',
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
		'parent_id',
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

	public function c_design_room()
	{
		return $this->belongsTo(CDesignRoom::class, 'parent_id');
	}

	public function designs()
	{
		return $this->hasMany(Design::class, 'design_room_type_id');
	}
}
