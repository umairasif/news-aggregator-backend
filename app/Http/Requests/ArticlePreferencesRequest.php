<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticlePreferencesRequest extends FormRequest
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
   * @return array<string, mixed>
   */
  public function rules()
  {
    return [
      'sources' => 'nullable|array',
      'sources.*' => 'string',
      'categories' => 'nullable|array',
      'categories.*' => 'string',
      'authors' => 'nullable|array',
      'authors.*' => 'string',
    ];
  }

  /**
   * Handle a failed validation attempt.
   * @param Validator $validator
   * @return mixed
   */
  protected function failedValidation(Validator $validator): mixed
  {
    throw new HttpResponseException(response()->json([
      'message' => 'Validation error',
      'errors' => $validator->errors(),
    ], 422));
  }
}