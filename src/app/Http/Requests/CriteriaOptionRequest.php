<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CriteriaOptionRequest extends FormRequest
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
    public function getRules(): array
    {
        return [
            'keterangan' => ['required'],
            'bobot' => ['required'],
        ];
    }

    public function validate(): array
    {
        $errors = [];

        $validator = Validator::make($this->all(), $this->getRules());

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
