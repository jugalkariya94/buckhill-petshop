<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;


/**
 * Class APIRequest
 * @package App\Http\Requests
 *
 * Base class for API requests validation
 */
class APIRequest extends FormRequest
{
    /**
     * @param Validator $validator
     * @return mixed
     * @throws HttpResponseException
     *
     * Override the failedValidation method to return a JSON response
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['success' => 'false', 'errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
