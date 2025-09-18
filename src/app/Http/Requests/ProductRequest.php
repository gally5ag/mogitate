<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 商品名
            'name'        => ['required', 'string', 'max:100'],

            // 値段：整数・0〜10000
            'price'       => ['required', 'integer', 'between:0,10000'],

            // 季節：チェックボックス（配列）で春/夏/秋/冬のいずれかを1つ以上
            'season'      => ['required', 'array', 'min:1'],
            'season.*'    => ['in:春,夏,秋,冬'],

            // 商品説明：必須・120文字以内
            'description' => ['required', 'string', 'max:120'],

            // 商品画像：登録時は必須、拡張子は .png / .jpeg のみ
            'image'       => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'image',
                'mimes:png,jpeg',   // ← 要件に合わせて jpg を許可しない
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // 商品名
            'name.required' => '商品名を入力してください',

            // 値段
            'price.required' => '値段を入力してください',
            'price.integer'  => '数値で入力してください',
            'price.between'  => '0~10000円以内で入力してください',

            // 季節
            'season.required' => '季節を選択してください',
            'season.array'    => '季節を選択してください',
            'season.min'      => '季節を選択してください',
            'season.*.in'     => '季節を選択してください',

            // 商品説明
            'description.required' => '商品説明を入力してください',
            'description.max'      => '120文字以内で入力してください',

            // 商品画像
            'image.required' => '商品画像を登録してください',
            'image.image'    => '「.png」または「.jpeg」形式でアップロードしてください',
            'image.mimes'    => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => '商品名',
            'price'       => '値段',
            'season'      => '季節',
            'description' => '商品説明',
            'image'       => '商品画像',
        ];
    }
}
