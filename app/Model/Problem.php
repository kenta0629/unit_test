<?php

namespace App\Model;

use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Problem extends Model
{
    /** バリデーションルール */
    public $rules =  [
        'branch_name' => 'required|max:255',
        'todo' => 'required',
        'expiration' => 'required|date',
    ];

    /** バリデーションエラーメッセージ */
    public $messages = [
        'branch_name.required' => 'ブランチ名は必須です',
        'branch_name.max' => 'ブランチ名は最大255文字です',
        'todo.required' => 'TODOリストは必須です',
        'expiration.required' => '有効期限は必須です',
        'expiration.date' => '有効期限はYYYY/MM/DDの形式で入力してください'
    ];

    /**
     * バリデーション
     *
     * @param array $todo 未完了タスク
     * @throws Exception
     */
    public function validateTasks(array $todo)
    {
        $validator = Validator::make($todo, $this->rules, $this->messages);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->all());
        }
    }
}
