<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class TuitionRequest extends FormRequest
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
        $rules = [];
        foreach ($this->get('biaya') as $key => $val) {
            $rules["biaya.{$key}.nilai"] = ["required"];
            $rules["biaya.{$key}.bobot_minimal"] = ["required_without:biaya.{$key}.bobot_maksimal"];
            $rules["biaya.{$key}.bobot_maksimal"] = ["required_without:biaya.{$key}.bobot_minimal"];
        }
        return $rules;
    }

    public function getMessages(): array
    {
        $messages = [
            'required_without' => ':attribute wajib diisi.'
        ];
        return $messages;
    }

    public function validate(): array
    {
        $errors = [];

        $validator = Validator::make($this->all(), $this->getRules(), $this->getMessages());

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
