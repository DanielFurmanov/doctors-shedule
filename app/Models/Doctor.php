<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property int consult_duration
 * @property string avatar
 *
 * @property Collection|Schedule[] schedules
 */
class Doctor extends Model {
	const AVATARS_FOLDER = 'img';

	public function schedules() {
		return $this->hasMany(Schedule::class);
	}

	public function getAvatarAttribute($value) {
		return static::AVATARS_FOLDER.'/'.$value;
	}
}
