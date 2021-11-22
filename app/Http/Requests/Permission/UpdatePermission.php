<?php

namespace App\Http\Requests\Permission;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePermission extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->permission);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', Rule::unique('permissions', 'name')->where("guard_name",$this->guard_name)->ignore($this->permission->getKey(), $this->permission->getKeyName()), 'string'],
            'title' => ['nullable', 'string'],
            'guard_name' => ['sometimes', Rule::unique('permissions', 'guard_name')->where("name",$this->name)->ignore($this->permission->getKey(), $this->permission->getKeyName()), 'string'],
                    
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function sanitizedArray(): array
    {
        $sanitized = $this->validated();


        //Add your code for manipulation with request data here

        return $sanitized;
    }
    /**
    * Return modified (sanitized data) as a php object
    * @return  object
    */
    public function sanitizedObject(): object {
        $sanitized = $this->sanitizedArray();
        return json_decode(collect($sanitized));
    }
}
