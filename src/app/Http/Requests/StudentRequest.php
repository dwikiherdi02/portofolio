<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class StudentRequest extends FormRequest
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
    public function getRulesFetchingExcelData(): array
    {
        return [
            'berkas' => [
                'required',
                File::types(['xls', 'xlsx'])
            ],
        ];
    }

    public function getRulesStoringData(): array
    {
        return [
            'nis' => ['required', 'unique:App\Models\Siswa,nis'],
            'nama' => ['required'],
        ];
    }

    public function validateFetchingExcelData(): array
    {
        $errors = [];

        $validator = Validator::make(['berkas' => $this->file('berkas')], $this->getRulesFetchingExcelData());

        if ($validator->fails()) {
            $errors = $validator->messages()->get('*');
            $mappedErr = Arr::map($errors, function (array $value) {
                return Arr::first($value);
            });
            $errors = $mappedErr;
        }
        return $errors;
    }

    public function validateProcessStoringData(): array
    {
        $errors = [];

        $validator = Validator::make($this->all(), $this->getRulesStoringData());

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
