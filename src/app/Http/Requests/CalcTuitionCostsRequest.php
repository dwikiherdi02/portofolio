<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class CalcTuitionCostsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function getRulesStore(): array
    {
        return [
            'berkas' => [
                'required',
                File::types(['xls', 'xlsx'])
            ],
            'id_tahun_ajaran' => ['required']
        ];
    }

    public function validateStore(): array
    {
        $errors = [];
        $request = [
            'berkas' => $this->has('berkas') ? $this->file('berkas') : null,
            'id_tahun_ajaran' => $this->get('id_tahun_ajaran'),
        ];

        $validator = Validator::make($request, $this->getRulesStore());
        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            $mappedErr = Arr::map($errors, function (array $value) {
                return Arr::first($value);
            });
            $errors = $mappedErr;
        }
        return $errors;
    }
}
