<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilePostRequest extends FormRequest
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
		$max_size = 1024*50; // 50 Mb
        return [
            'file' => "required|file|max:$max_size",
			'path' => 'sometimes|string|max:255'
        ];
    }
	
	public function wantsJson()
    {
        return true;
    }
}
