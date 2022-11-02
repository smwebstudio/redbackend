<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EstateDocument
 * 
 * @property int $id
 * @property string|null $comment_arm
 * @property string|null $comment_eng
 * @property string|null $comment_ru
 * @property string|null $comment_ar
 * @property int|null $document_type_id
 * @property int|null $estate_id
 * @property string|null $path
 * @property string|null $path_thumb
 * @property string|null $file_name
 * @property bool|null $is_public
 *
 * @package App\Models
 */
class EstateDocument extends Model
{
	protected $table = 'estate_document';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'document_type_id' => 'int',
		'estate_id' => 'int',
		'is_public' => 'bool'
	];

	protected $fillable = [
		'comment_arm',
		'comment_eng',
		'comment_ru',
		'comment_ar',
		'document_type_id',
		'estate_id',
		'path',
		'path_thumb',
		'file_name',
		'is_public'
	];
}
