<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ApiMultiLanguage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 *
 * @property int $id
 * @property int|null $contact_id
 * @property int|null $version
 * @property int|null $estate_type_id
 * @property int|null $estate_contract_type_id
 * @property int|null $location_province_id
 * @property int|null $location_city_id
 * @property int|null $location_community_id
 * @property int|null $location_street_id
 * @property int|null $currency_id
 * @property float|null $price_from
 * @property float|null $price_from_usd
 * @property float|null $price_to
 * @property float|null $price_to_usd
 * @property float|null $area_from
 * @property float|null $area_to
 * @property int|null $room_count_from
 * @property int|null $room_count_to
 * @property int|null $building_type_id
 * @property int|null $repairing_type_id
 * @property bool|null $new_construction
 * @property int|null $broker_id
 * @property int|null $info_source_id
 * @property string|null $location_building
 * @property int|null $contact_status_id
 * @property Carbon|null $status_changed_on
 * @property bool|null $is_urgent
 * @property Carbon|null $archive_till_date
 * @property string|null $archive_comment
 * @property bool|null $is_from_public
 *
 * @package App\Models
 */
class Client extends Model
{
	protected $table = 'client';
	public $incrementing = false;
	public $timestamps = false;

    use ApiMultiLanguage;
    protected array $multi_lang = [
        'name',
    ];

	protected $casts = [
		'id' => 'int',
		'contact_id' => 'int',
		'version' => 'int',
		'estate_type_id' => 'int',
		'estate_contract_type_id' => 'int',
		'location_province_id' => 'int',
		'location_city_id' => 'int',
		'location_community_id' => 'int',
		'location_street_id' => 'int',
		'currency_id' => 'int',
		'price_from' => 'float',
		'price_from_usd' => 'float',
		'price_to' => 'float',
		'price_to_usd' => 'float',
		'area_from' => 'float',
		'area_to' => 'float',
		'room_count_from' => 'int',
		'room_count_to' => 'int',
		'building_type_id' => 'int',
		'repairing_type_id' => 'int',
		'new_construction' => 'bool',
		'broker_id' => 'int',
		'info_source_id' => 'int',
		'contact_status_id' => 'int',
		'is_urgent' => 'bool',
		'is_from_public' => 'bool'
	];

	protected $dates = [
		'status_changed_on',
		'archive_till_date'
	];

	protected $fillable = [
		'contact_id',
		'version',
		'estate_type_id',
		'estate_contract_type_id',
		'location_province_id',
		'location_city_id',
		'location_community_id',
		'location_street_id',
		'currency_id',
		'price_from',
		'price_from_usd',
		'price_to',
		'price_to_usd',
		'area_from',
		'area_to',
		'room_count_from',
		'room_count_to',
		'building_type_id',
		'repairing_type_id',
		'new_construction',
		'broker_id',
		'info_source_id',
		'location_building',
		'contact_status_id',
		'status_changed_on',
		'is_urgent',
		'archive_till_date',
		'archive_comment',
		'is_from_public'
	];
}
