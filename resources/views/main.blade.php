<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	{{-- todo You should create CSS files in resources/assets/css, and then mix it by elixir --}}
	<link rel="stylesheet" href="all.css">

	{{--those scripts use CDN because of webpack bug of moments locale--}}
	<script src="http://momentjs.com/downloads/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/ru.js"></script>

	{{-- todo  You should create JS files in resources/assets/js, and then mix it by elixir --}}
	<script src="/js/all.js"></script>
</head>
<body ng-app="doctorsScheduleApp" ng-controller="doctorsController" ng-cloak>
	<div layout="row" layout-align="center">
		<md-content flex="40">
			<md-toolbar>
				<div class="md-toolbar-tools">
					<md-button ng-show="selectedDoctor" aria-label="Go Back" ng-click="selectedDoctor = null">
						Назад
					</md-button>
					<h2>
						<span>Запись на приём</span>
					</h2>
					<span flex=""></span>

					<md-input-container ng-hide="selectedDoctor" class="md-docs-dark-theme md-input-has-value">
						<label for="search">Искать по ФИО</label>
						<input ng-model="searchText"
							   class="ng-pristine ng-valid md-input ng-not-empty ng-touched"
							   id="search"
							   aria-invalid="false"
						>
						<div class="md-errors-spacer"></div>
					</md-input-container>

				</div>
			</md-toolbar>

			<md-content ng-if="! selectedDoctor" >
				<md-card ng-repeat="doctor in filteredDoctors = (doctors | filter:searchText)" layout="row">
					<md-card-title flex="15">
						<div class="md-media-sm card-media" md-colors="{background: 'default-accent'}">
							<img ng-src="[[doctor.avatar]]"
								 class="md-card-image"
								 alt="[[doctor.name]]"
								 style="max-height:100px;"
							>
						</div>
					</md-card-title>
					<md-card-content flex="70">
						<h2>[[doctor.name]]</h2>
						<p>Тут могло быть описание</p>
					</md-card-content>
					<md-card-actions layout="column" layout-align="end center" flex="15">
						<md-button ng-click="selectDoctor([[doctor.id]])">Записаться</md-button>
					</md-card-actions>
				</md-card>

				<div ng-show=" ! filteredDoctors.length">Не найдено ни одного врача</div>
			</md-content>

			<div ng-if="selectedDoctor" style="overflow-x:hidden">
				<md-card>
					<md-card-title flex="15">
						<div class="md-media-sm card-media" md-colors="{background: 'default-accent'}">
							<img ng-src="[[selectedDoctor.avatar]]"
								 class="md-card-image"
								 alt="image caption"
								 style="max-height: 100px;"
							>
						</div>
					</md-card-title>
					<md-card-content>
						<h2>[[selectedDoctor.name]]</h2>
					</md-card-content>
					<md-card-actions layout="row" layout-align="end center">
						<p>Выберите дату</p>
						<md-datepicker ng-model="selectedDate"
									   md-placeholder="Выберите дату"
									   md-min-date="minDate"
									   md-max-date="maxDate"
									   ng-change="setSelectedDate(selectedDate)"
						>
						</md-datepicker>
					</md-card-actions>
				</md-card>

				<md-list>
					<md-subheader class="md-no-sticky" ng-show="selectedDate">
						Дата <b><mydate>[[selectedDate | date:'dd.MM.yyyy']]</mydate></b>.
						<span  ng-if="schedule_intervals.length">Выберите время:</span>
					</md-subheader>

					<md-list-item ng-repeat="interval in schedule_intervals"
								  ng-click="preselectConsultation($event, interval.time)"
					>
						<p>
							<mydate md-colors="{background: '[[ interval.vacant ? 'light-green' : 'red' ]]'}">
								[[interval.time * 1000 | date:'HH:mm']]
							</mydate>
						</p>
						<md-button ng-disabled=" ! interval.vacant"
								   class="md-secondary"
								   ng-click="preselectConsultation($event, interval.time)"
						>
							[[ interval.vacant ? 'Выбрать' : 'Занято']]
						</md-button>
					</md-list-item>

					<md-list-item ng-if="message" layout-align="end center">
						[[ message ]]
					</md-list-item>
				</md-list>
			</div>
		</md-content>
	</div>
</body>
</html>
