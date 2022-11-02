<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstateRentContract
 * 
 * @property int $id
 * @property int|null $estate_id
 * @property float|null $initial_price
 * @property int|null $initial_price_currency_id
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property int|null $renter_id
 * @property int|null $agent_id
 * @property float|null $final_price
 * @property int|null $final_price_currency_id
 * @property int|null $index_col
 * @property string|null $comment_arm
 * @property string|null $comment_eng
 * @property string|null $comment_ru
 * @property string|null $comment_ar
 *
 * @package App\Models
 */
class EstateRentContract extends Model
{
	protected $table = 'estate_rent_contract';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'estate_id' => 'int',
		'initial_price' => 'float',
		'initial_price_currency_id' => 'int',
		'renter_id' => 'int',
		'agent_id' => 'int',
		'final_price' => 'float',
		'final_price_currency_id' => 'int',
		'index_col' => 'int'
	];

	protected $dates = [
		'start_date',
		'end_date'
	];

	protected $fillable = [
		'estate_id',
		'initial_price',
		'initial_price_currency_id',
		'start_date',
		'end_date',
		'renter_id',
		'agent_id',
		'final_price',
		'final_price_currency_id',
		'index_col',
		'comment_arm',
		'comment_eng',
		'comment_ru',
		'comment_ar'
	];
}
