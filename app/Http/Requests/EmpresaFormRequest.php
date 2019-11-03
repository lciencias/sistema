<?php

namespace sistema\Http\Requests;

use sistema\Http\Requests\Request;

class EmpresaFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'=>'required|max:100',
            'direccion'=>'max:255',
        	'nombre_representante'=>'required|max:70',
        	'paterno_representante'=>'required|max:70',
        	'materno_representante'=>'max:70',
        	//'email_representante' => 'required|email|max:255|unique:empresa,email_representante'. $this->id,
        	'rfc1'=>'min:0|max:4',
        	'rfc2'=>'min:0|max:6',
        	'rfc3'=>'min:0|max:3',
        	'razon_social'=>'min:0|max:70',
        	'logotipo'=>'min:0|max:100'
        ];
    }
}
