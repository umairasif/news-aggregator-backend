<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleSearchRequest extends FormRequest
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
      'q' => 'required|string|min:2',
    ];
  }

  /**
   * @return string[]
   */
  public function messages(): array
  {
    return [
      'q.required' => 'Please enter a search query',
      'q.string' => 'Please enter a valid search query',
      'q.min' => 'Query must be at least 2 characters long',
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
