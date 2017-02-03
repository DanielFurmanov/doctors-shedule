(function () {
	'use  strict';

	angular
		.module('doctorsScheduleApp', ['ngMaterial', 'doctorsApiService'], function ($interpolateProvider) {
			$interpolateProvider.startSymbol('[[');
			$interpolateProvider.endSymbol(']]');
		})
		.config(function($compileProvider, $mdDateLocaleProvider) {
			// shows calendar for datepicker; work around for this issue - https://github.com/angular/material/issues/10168
			$compileProvider.preAssignBindingsEnabled(true);

			// localize datepicker
			$mdDateLocaleProvider.months = ["Январь", "Февраль", "Март","Апрель","Май", "Июнь", "Июль","Август",
				"Сентябрь","Октябрь","Ноябрь","Декабрь"];
			$mdDateLocaleProvider.shortMonths = ["янв", "фев", "мар","апр","май", "июн", "июл","авг",
				"сен","окт","ноя","дек"];
			$mdDateLocaleProvider.days = ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'];
			$mdDateLocaleProvider.shortDays = [ 'Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];

			// change week display to start on Monday.
			$mdDateLocaleProvider.firstDayOfWeek = 1;

			// Example uses moment.js to parse and format dates.
			$mdDateLocaleProvider.parseDate = function(dateString) {
				var m = moment(dateString, 'L', true);
				return m.isValid() ? m.toDate() : new Date(NaN);
			};

			$mdDateLocaleProvider.formatDate = function(date) {
				var m = moment(date);
				return m.isValid() ? m.format('L') : '';
			};

			$mdDateLocaleProvider.monthHeaderFormatter = function(date) {
				return $mdDateLocaleProvider.shortMonths[date.getMonth()] + ' ' + date.getFullYear();
			};

			// In addition to date display, date components also need localized messages
			// for aria-labels for screen-reader users.

			$mdDateLocaleProvider.weekNumberFormatter = function(weekNumber) {
				return 'Неделя ' + weekNumber;
			};

			$mdDateLocaleProvider.msgCalendar = 'Календарь';
			$mdDateLocaleProvider.msgOpenCalendar = 'Открыть календарь';
		})
		.controller('doctorsController', ['$scope', '$mdDialog', '$mdToast', 'doctorsApi', doctorsController]);

	function doctorsController($scope, $mdDialog, $mdToast, doctorsApi) {
		$scope.doctors = [];
		$scope.selectedDoctor = null;
		$scope.available_dates = [];
		$scope.message = null;
		$scope.schedule_intervals = [];
		$scope.consultations = [];

		$scope.selectedDate = new Date();
		$scope.minDate = new Date();
		$scope.maxDate = new Date(
			$scope.minDate.getFullYear(),
			$scope.minDate.getMonth() + 1,// build schedule only for next month
			$scope.minDate.getDate());

		// load doctors on page load
		doctorsApi.getDoctors().then(function (response) {
				$scope.doctors = response.data;
			},
			function (response) {
				systemError();
			}
		);

		$scope.selectDoctor = function (id) {
			$scope.selectedDoctor = getOneById($scope.doctors, id);
			// load doctors schedule
			loadSchedule();
			$scope.selectedDate = null;
		};

		$scope.$watch('selectedDate', function (newValue, oldValue) {
			$scope.message = null;

			if ( ! newValue || ! $scope.selectedDoctor) {
				return null;
			}

			var schedule = pickScheduleByDate(newValue);

			if (schedule) {
				loadConsultations(schedule);
			}
			else {
				$scope.message = 'В этот день врач не работает';
				$scope.schedule_intervals = [];
			}
		});


		// this is needed for making $watch work with datepicker
		$scope.setSelectedDate = function (date) {
			$scope.selectedDate = date;
		};

		/**
		 * show confirmational modal with all info
		 * @param event
		 * @param unix_time
		 */
		$scope.preselectConsultation = function(event, unix_time) {
			unix_time *= 1000;
			console.log(unix_time);
			console.log(moment(unix_time));
			console.log(new Date(unix_time));
			var confirmation = $mdDialog.confirm()
				.title('Подтвердите информацию')
				.textContent(' Консультация у врача ' + $scope.selectedDoctor.name +'. Дата: ' + moment(unix_time).format('LLLL'))
				.ok('Всё верно. Записаться!')
				.cancel('Отмена')
				.targetEvent(event);

			$mdDialog.show(confirmation).then(function() {
				doctorsApi.postConsultation({
					doctor_id : $scope.selectedDoctor.id,
					schedule_id : $scope.selectedSchedule.id,
					date : moment(unix_time).format('YYYY-MM-DD HH:mm:ss')
				})
					.then(function (response) {
						loadSchedule();
						loadConsultations(pickScheduleByDate($scope.selectedDate));
					})
				;
			}, function() {
				//
			});
		};

		$scope.resetAll = function() {
			$scope.selectedDoctor = null;
			$scope.available_dates = [];
			$scope.message = null;
			$scope.schedule_intervals = [];
			$scope.consultations = [];
		};

		/**
		 * loads schedule for selected doctor
		 */
		function loadSchedule () {
			doctorsApi.getSchedule($scope.selectedDoctor.id).then(function (response) {
					$scope.available_dates = response.data;
				}, function (response) {
					systemError();
			});
		}

		function loadConsultations(schedule) {
			var start = moment(schedule.start);
			var end = moment(schedule.end);
			$scope.consultations = [];
			$scope.schedule_intervals = [];

			doctorsApi.getConsultations(schedule.id).then(function (response) {
					$scope.consultations = response.data;
					$scope.schedule_intervals = generateIntervals(start, end);
				}, function (response) {
					systemError();
			});
		}

		function generateIntervals(start, end) {
			var resultIntervals = [];
			var scheduleIntervals = getUnixIntervalsOfDatePeriod(start, end);
			var consultationIntervals = [];

			$scope.consultations.forEach(function (consultation) {
				var consStart = moment(consultation.start);
				consultationIntervals.push(consStart.unix());
			});

			var intersection = scheduleIntervals.filter(function(n) {
				return consultationIntervals.indexOf(n) != -1;
			});

			// if intersection is the same length as whole schedule that means that schedule is full
			if (intersection.length === scheduleIntervals.length) {
				$scope.message = 'На этот день записи больше нет';
				return;
			}

			for (var i = 0; i < scheduleIntervals.length; i++) {
				var obj = {
					time : scheduleIntervals[i],
					vacant : intersection.indexOf(scheduleIntervals[i]) === -1
				};
				resultIntervals.push(obj)
			}

			return resultIntervals;
		}

		function getUnixIntervalsOfDatePeriod(start, end) {
			// WARNING .unix() returns SECONDS (Unix Epoch)
			var array = [];
			var step = $scope.selectedDoctor.consult_duration * 60; // *60 - minutes to seconds
			// generate array of unixdates with interval of doctors consultation duration
			for (var i = start.unix(); i < end.unix(); i += step) {
				array.push(i);
			}
			return array;
		}

		function pickScheduleByDate(on_this_day) {
			var dateToCompare = moment.utc(on_this_day).startOf('day');
			var dates = $scope.available_dates; // helping readability

			for (var i = 0; i < dates.length; i++) {
				// strip the time from schedule
				var thatDate = moment.utc(dates[i].start).startOf('day');

				if (dateToCompare.isSame(thatDate)) {
					$scope.selectedSchedule = dates[i];
					return dates[i];
				}
			}

			return false;
		}

		function getOneById(collection, id) {
			for (var i = 0; i < collection.length; i++) {
				if (collection[i].id == id) {
					return collection[i];
				}
			}

			// error - we found no doctor
			systemError();
		}

		function systemError() {
			$mdToast.show(
				$mdToast.simple()
					.textContent('При запросе произошла ошибка')
					.hideDelay(3000)
			);
		}
	}
})();
