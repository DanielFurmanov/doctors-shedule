<?php

use App\Models\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultations extends Migration {
	protected $table = 'consultations';
	protected $foreign_column = 'schedule_id';

	public function up() {
		Schema::create($this->table, function(Blueprint $t) {
			$t->increments('id');
			$t->integer($this->foreign_column)->unsigned();
			$t->timestamp('start');
			$t->timestamp('end');

			$t->foreign($this->foreign_column)
				->references('id')
				->on((new Schedule)->getTable())
				->onDelete('restrict')
				->onUpdate('restrict');

			$t->unique([$this->foreign_column, 'start']);

		});
	}

	public function down() {
		Schema::dropIfExists($this->table);
	}
}
