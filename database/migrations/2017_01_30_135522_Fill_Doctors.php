<?php

use App\Models\Doctor;
use Illuminate\Database\Migrations\Migration;

class FillDoctors extends Migration {

	public function up() {
		factory(Doctor::class, 100)->create();
	}

	public function down() {
		Doctor::query()->truncate();
	}
}
