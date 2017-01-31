<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int schedule_id
 * @property string start
 * @property string end
 */
class Consultation extends Model {
	public $timestamps = false;

	public function create(Doctor $doctor, array $input) {
		$this->schedule_id = $input['schedule_id'];

		$carbon = Carbon::parse($input['date']);
		$this->start = $carbon->format('Y-m-d H:i:s');
		$this->end = $carbon->addMinutes($doctor->consult_duration)->format('Y-m-d H:i:s');

		return $this->save();
	}
}
