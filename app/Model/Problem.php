<?php

namespace App\Model;

use App\Exceptions\ValidationException;
use Carbon\Carbon;
use DB;
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

    /**
     *
     * @param object $task タスクデータ
     * @return null
     */
    public function updateAllTodo(object $task)
    {
        $now = Carbon::now();

        $record = DB::update('
            update problems
            set deleted_at = :datetime
            where task_id = :id
        ', ['id' => $task->id, 'datetime' => $now]);

        $progress = $record / $task->cnt * 100;

        if ($progress > 0) {
            $record = DB::update('
                update tasks
                set num_finished = num_finished + :num,
                    num_remaining = 0,
                    progress = 100.00
                where id = :id
            ', ['id' => $task->id, 'num' => $record]);
        }
    }

    /**
     *
     * @param string $detail TODOリスト内容
     * @return null
     */
    public function updateTodoDetail(string $detail)
    {
        DB::update('
            update problems
            set todo = :todo
            where todo = "どこで手を抜くかを考える"
        ', ['todo' => $detail]);
    }
}
