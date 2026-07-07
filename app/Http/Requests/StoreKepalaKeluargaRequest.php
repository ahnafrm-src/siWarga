<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreKepalaKeluargaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

    

        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nama_kepala' => 'required|string',
            'alamat' => 'required',
            'no_hp' => 'min:15|nullable',
        ];

        if($this->isMethod('post')){
            $rules['no_kk'] = 'required|min:16|max:16|unique:kepala_keluarga,no_kk';
        }else{
            $model = $this->route('kepala_keluarga');
            $id = is_object($model) ? $model->id : $model;

            $rules['no_kk'] = 'required|min:16|max:16|unique:kepala_keluarga,no_kk,' . $id;
        }

        return $rules;
    }
}
