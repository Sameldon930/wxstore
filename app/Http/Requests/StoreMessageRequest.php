<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'title' => 'required|max:255',
            'content' => 'required',
            'text'=>'required|max:30'
        ];
    }

    public function messages(){
        return [

            'title.required'=> '标题不能为空',
            'title.max' => '标题字数过多',
            'content.required' => '消息内容不能为空',
            'text.required'=>'简介内容不能为空',
            'text.max'=>'简介字数过多'
        ];
    }
}
