<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProfessionalMenuLocationCity
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int|null $location_city_id
 *
 * @package App\Models
 */
class ProfessionalMenuLocationCity extends Model
{
	protected $table = 'professional_menu_location_city';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'user_id' => 'int',
		'location_city_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'location_city_id'
	];
}
