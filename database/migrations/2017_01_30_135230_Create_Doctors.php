<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctors extends Migration {
	protected $table = 'doctors';
	
	public function up() {
		Schema::create($this->table, function(Blueprint $t) {
			$t->increments('id');
			$t->string('name');
			$t->integer('consult_duration')->unsigned();
			$t->string('avatar');
			$t->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists($this->table);
	}
}
