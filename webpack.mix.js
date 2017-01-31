const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
	.combine([
		'node_modules/angular-material/angular-material.min.css',
	], 'public/all.css')
	.js([
		// 'node_modules/moment/src/locale/ru.js', // those scripts use CDN because of webpack bug of moments locale
		// 'node_modules/moment/src/moment.js', // those scripts use CDN because of webpack bug of moments locale
		'node_modules/angular/angular.min.js',
		'node_modules/angular-animate/angular-animate.min.js',
		'node_modules/angular-aria/angular-aria.min.js',
		'node_modules/angular-material/angular-material.min.js',
		'resources/assets/angular/api.js',
		'resources/assets/angular/doctorsScheduleApp.js',
	], 'public/js/all.js')
	.copy('resources/assets/img', 'public/img');
