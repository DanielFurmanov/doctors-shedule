<?php

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class FillSchedules extends Migration {
	public function up() {
		DB::transaction(function() {
			/** @var Collection|Doctor[] $doctors */
			$doctors = Doctor::all();

			foreach ($doctors as $doctor) {
				$faker = Faker\Factory::create();

				// assume that doctors work different number of days per month
				$counter = $faker->numberBetween(15, 22);

				for ($i = 0; $i <= $counter; $i++) {
					$date_start = \Carbon\Carbon::now()->addDays($i)->setTime($faker->numberBetween(0,15),$faker->numberBetween(0, 59));
					$hours = $faker->numberBetween(2,5);
					$date_end = \Carbon\Carbon::parse($date_start)->addHours($hours);

					$schedule = new Schedule();
					$schedule->doctor_id = $doctor->id;
					$schedule->start = $date_start->format('Y-m-d H:i:s');
					$schedule->end = $date_end->format('Y-m-d H:i:s');
					$schedule->save();
				}
			}
		});
	}

	public function down() {
		Schedule::query()->truncate();
	}
}
