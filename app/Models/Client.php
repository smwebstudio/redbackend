<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ApiMultiLanguage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
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
    use CrudTrait;
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

    public function getTypeListAttribute($relation, $column)
    {
        if ($this->$relation->isNotEmpty()) {
            return $this->$relation->pluck($column)->implode(', ');
        }
        return null;
    }

    public function building_types()
    {
        return $this->hasManyThrough(
            CBuildingType::class,
            'App\Models\ClientBuildingType',
            'client_id',
            'id',
            'id',
            'building_type_id',
        );
    }

    public function repairing_types()
    {
        return $this->hasManyThrough(
            CRepairingType::class,
            'App\Models\ClientRepairingType',
            'client_id',
            'id',
            'id',
            'repairing_type_id',
        );
    }

    public function building_project_types()
    {
        return $this->hasManyThrough(
            CBuildingProjectType::class,
            'App\Models\ClientBuildingProjectType',
            'client_id',
            'id',
            'id',
            'building_project_type_id',
        );
    }

    public function land_use_types()
    {
        return $this->hasManyThrough(
            CLandUseType::class,
            'App\Models\ClientLandUseType',
            'client_id',
            'id',
            'id',
            'land_use_type_id',
        );
    }

//    public function communities()
//    {
//        return $this->hasManyThrough(
//            CLocationCommunity::class,
//            'App\Models\ClientCommunity',
//            'client_id',
//            'id',
//            'id',
//            'community_id',
//        );
//    }

    public function communities()
    {
        return $this->belongsToMany(
            CLocationCommunity::class,
            'client_community',
            'client_id',
            'community_id',
            'id',
            'id',
        );
    }

    public function location_city()
    {
        return $this->belongsTo(CLocationCity::class, 'location_city_id');
    }

    public function location_province()
    {
        return $this->belongsTo(CLocationProvince::class, 'location_province_id');
    }

    public function location_street()
    {
        return $this->belongsTo(CLocationStreet::class, 'location_street_id');
    }

    public function estate_type()
    {
        return $this->belongsTo(CEstateType::class, 'estate_type_id');
    }

    public function contract_type()
    {
        return $this->belongsTo(CContractType::class, 'estate_contract_type_id');
    }

    public function getClientContractTypeRentsAttribute()
    {
        return $this->contract_type()->where('id', '!=', 1)->first();
    }

    public function contact_status()
    {
        return $this->belongsTo(CContactStatus::class, 'contact_status_id');
    }

    public function broker()
    {
        return $this->belongsTo(RealtorUser::class, 'broker_id');
    }

    public function infoSource()
    {
        return $this->belongsTo(RealtorUser::class, 'info_source_id');
    }


    public function getBuildingTypesListAttribute()
    {
        return $this->getTypeListAttribute('building_types', 'name_arm');
    }

    public function getRepairingTypesListAttribute()
    {
        return $this->getTypeListAttribute('repairing_types', 'name_arm');
    }

    public function getBuildingProjectTypesListAttribute()
    {
        return $this->getTypeListAttribute('building_project_types', 'name_arm');
    }

    public function getCommunityListAttribute()
    {
        return $this->getTypeListAttribute('communities', 'name_arm');
    }

    public function getLandUseTypesListAttribute()
    {
        return $this->getTypeListAttribute('land_use_types', 'name_arm');
    }
}
