(function () {
	angular.module('doctorsApiService', [])
		.service('doctorsApi', ['$http', function ($http) {
			this.getDoctors = function () {
				return $http.get('api/doctors');
			};

			this.getSchedule = function (doctor_id) {
				return $http.get('api/doctor/' + doctor_id + '/schedule')
			};

			this.getConsultations = function (schedule_id) {
				return $http.get('api/schedule/' + schedule_id + '/consultations')
			};

			this.postConsultation = function (data) {
				return $http.post('api/consultation/create', data);
			}
		}]);
})();
