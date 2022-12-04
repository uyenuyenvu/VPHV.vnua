<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;

class UpdateRoleRequest extends BaseRequest
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
            'name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'tên vai trò'
        ];
    }
}
