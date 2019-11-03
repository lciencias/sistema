<?php

namespace sistema\Http\Requests;

use sistema\Http\Requests\Request;

class PermisoFormRequest extends Request {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
				'name' => 'required|max:255',
		];
	}
}
