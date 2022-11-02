<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectDocument
 * 
 * @property int $id
 * @property string|null $comment_arm
 * @property string|null $comment_eng
 * @property string|null $comment_ru
 * @property string|null $comment_ar
 * @property int|null $document_type_id
 * @property int|null $project_id
 * @property string|null $path
 * @property string|null $path_thumb
 * @property string|null $file_name
 *
 * @package App\Models
 */
class ProjectDocument extends Model
{
	protected $table = 'project_document';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'document_type_id' => 'int',
		'project_id' => 'int'
	];

	protected $fillable = [
		'comment_arm',
		'comment_eng',
		'comment_ru',
		'comment_ar',
		'document_type_id',
		'project_id',
		'path',
		'path_thumb',
		'file_name'
	];
}
