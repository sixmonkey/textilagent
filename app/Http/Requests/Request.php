<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $nameSpace = rtrim(app()->getNamespace(), '\\') . '\Http\Requests\\';
        $model = (class_basename($this->route()->getController()->model));
        $action = (ucfirst($this->route()->getActionMethod()));

        $className = $nameSpace . $action . $model . 'Request';

        if (class_exists($className)) {
            $request = new $className();
            return $request->rules();
        }

        return [];
    }
}
