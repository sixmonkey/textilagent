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
     * @param string $type
     * @return array
     */
    protected function getFromRelatedRequestClass($type = 'rules'): array
    {
        $nameSpace = rtrim(app()->getNamespace(), '\\') . '\Http\Requests\\';
        $model = (class_basename($this->route()->getController()->model));
        $action = (ucfirst($this->route()->getActionMethod()));


        $className = $nameSpace . $action . $model . 'Request';

        if (class_exists($className)) {
            $request = $className::createFrom($this);
            return call_user_func([$request, $type]);
        }

        return [];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return $this->getFromRelatedRequestClass();
    }

    /**
     * Get the messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return $this->getFromRelatedRequestClass('messages');
    }
}
