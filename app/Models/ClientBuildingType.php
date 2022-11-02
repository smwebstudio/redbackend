<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientBuildingType
 * 
 * @property int $id
 * @property string|null $client_id
 * @property string|null $building_type_id
 *
 * @package App\Models
 */
class ClientBuildingType extends Model
{
	protected $table = 'client_building_type';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'client_id',
		'building_type_id'
	];
}
