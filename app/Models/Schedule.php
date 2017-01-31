<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 *
 * @property int doctor_id
 * @property string start
 * @property string end
 * 
 * @property Consultation[] consultations
 */
class Schedule extends Model {
	public $timestamps = false;

	public function consultations() {
		return $this->hasMany(Consultation::class);
	}
}
