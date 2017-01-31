<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Doctor;

$factory->define(Doctor::class, function () {
	$faker = Faker\Factory::create('ru_RU'); // create Russian Faker
	$avatars = [
		'1.jpg',
		'2.jpg',
		'3.jpg',
		'4.png',
		'5.jpg',
		'6.jpg',
		'7.png',
	];
	return [
		'name' => $faker->name,
		'avatar' => $faker->randomElement($avatars),
		'consult_duration' => $faker->randomElement([30, 45, 60]), // simulate different consultation durations
	];
});
