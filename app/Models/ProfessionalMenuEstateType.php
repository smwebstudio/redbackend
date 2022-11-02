<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfessionalMenuEstateType
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int|null $estate_type_id
 *
 * @package App\Models
 */
class ProfessionalMenuEstateType extends Model
{
	protected $table = 'professional_menu_estate_type';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'user_id' => 'int',
		'estate_type_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'estate_type_id'
	];
}
