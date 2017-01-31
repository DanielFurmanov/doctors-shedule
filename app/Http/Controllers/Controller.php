<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;

class Controller extends BaseController {
	use ValidatesRequests;

	protected $mainData = [];

	public function getIndex() {
		$this->mainData['title'] = trans('title.doctors-schedule');
		return view('main', $this->mainData);
	}

	public function getDoctors() {
		return response()->json(Doctor::all());
	}

	public function getSchedule($doctor_id) {
		/** @var Doctor $doctor */
		$doctor = Doctor::findOrFail($doctor_id);
		return response()->json($doctor->schedules);
	}

	public function getConsultations($schedule_id) {
		/** @var Schedule $schedule */
		$schedule = Schedule::findOrFail($schedule_id);
		return response()->json($schedule->consultations);
	}
	
	public function postConsultation(Request $request) {
		$this->validate($request, [
			'doctor_id' => 'bail|required|exists:'.(new Doctor())->getTable().',id',
			'schedule_id' => 'bail|required|exists:'.(new Schedule())->getTable().',id',
			'date' => 'required|date_format:Y-m-d H:i:s|after:yesterday|before: + 1 month',
		]);
		$doctor = Doctor::findOrFail(Input::get('doctor_id'));
		$consultation = new Consultation();
		$consultation->create($doctor, Input::all());
		return ;
	}
}
