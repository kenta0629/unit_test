<?php

namespace App\Model;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * hasMany
     *
     * @return void
     */
    public function problems()
    {
        return $this->hasMany('App\Model\Problem');
    }

    /**
     *
     * @param object $task タスクデータ
     * @return null
     */
    public function updateAllTodo(object $task)
    {
        $now = Carbon::now();

        $record = DB::delete('
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
}
