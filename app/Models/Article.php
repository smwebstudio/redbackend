<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\ApiMultiLanguage;
use App\Traits\Models\HasFilePath;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 *
 * @property int $id
 * @property int|null $article_type_id
 * @property string|null $title_arm
 * @property string|null $title_eng
 * @property string|null $title_ru
 * @property string|null $title_ar
 * @property string|null $content_arm
 * @property string|null $content_eng
 * @property string|null $content_ru
 * @property string|null $content_ar
 * @property int|null $created_by
 * @property Carbon|null $created_on
 * @property int|null $last_modified_by
 * @property Carbon|null $last_modified_on
 * @property string|null $main_image_file_name
 * @property string|null $main_image_file_path
 * @property string|null $main_image_file_path_thumb
 * @property bool|null $is_published
 * @property bool|null $is_approved
 * @property int|null $view_count
 * @property string|null $metatitle_eng
 * @property string|null $metatitle_arm
 * @property string|null $metatitle_ru
 * @property string|null $metatitle_ar
 *
 * @property CArticleType|null $c_article_type
 *
 * @package App\Models
 */
class Article extends Model
{
    use CrudTrait;
    use HasFilePath;

	protected $table = 'article';
	public $incrementing = false;
	public $timestamps = false;

    use ApiMultiLanguage;
    protected  $multi_lang = [
        'name',
    ];

	protected $casts = [
		'id' => 'int',
		'article_type_id' => 'int',
		'created_by' => 'int',
		'last_modified_by' => 'int',
		'is_published' => 'bool',
		'is_approved' => 'bool',
		'view_count' => 'int'
	];

	protected $dates = [
		'created_on',
		'last_modified_on'
	];

	protected $fillable = [
		'article_type_id',
		'title_arm',
		'title_eng',
		'title_ru',
		'title_ar',
		'content_arm',
		'content_eng',
		'content_ru',
		'content_ar',
		'created_by',
		'created_on',
		'last_modified_by',
		'last_modified_on',
		'main_image_file_name',
		'main_image_file_path',
		'main_image_file_path_thumb',
		'is_published',
		'is_approved',
		'view_count',
		'metatitle_eng',
		'metatitle_arm',
		'metatitle_ru',
		'metatitle_ar'
	];

	public function c_article_type()
	{
		return $this->belongsTo(CArticleType::class, 'article_type_id');
	}
}
