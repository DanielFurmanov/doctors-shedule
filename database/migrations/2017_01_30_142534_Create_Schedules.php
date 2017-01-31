<?php

use App\Models\Doctor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedules extends Migration {
	protected $table = 'schedules';
	protected $foreign_column = 'doctor_id';

	public function up() {
		Schema::create($this->table, function(Blueprint $t) {
			$t->increments('id');
			$t->integer($this->foreign_column)->unsigned();
			$t->timestamp('start');
			$t->timestamp('end');

			$t->foreign($this->foreign_column)
				->references('id')
				->on((new Doctor)->getTable())
				->onDelete('restrict')
				->onUpdate('restrict');

			$t->unique([$this->foreign_column, 'start']);

			// todo there should be no ability to have multiple starting dates of the same date for one doctor (index??)
		});
	}

	public function down() {
		Schema::dropIfExists($this->table);
	}
}
