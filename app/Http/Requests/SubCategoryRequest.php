<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
        $id = request()->id;

        return [
            'name'              => 'required|unique:sub_categories,name,'.$id,
            'status'            => 'required',
            'category_id'       => 'required',
            // 'subcategory_image'    => 'mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg',
        ];
    }
}
