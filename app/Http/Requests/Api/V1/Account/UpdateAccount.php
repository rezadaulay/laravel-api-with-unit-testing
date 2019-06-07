<?php

namespace App\Http\Requests\Api\V1\Account;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UpdateAccount extends FormRequest
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
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email,'. Auth::id(),
			'password'=> 'min:6|confirmed',
            // 'signature_file'=> 'image|max:'.(\config('user.signature_file.upload_size')/1000),
        ];
    }
}
