<?php

namespace Tests\Unit;

use App\Exceptions\ValidationException;
use App\Model\Problem;
use App\Model\Task;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class WorkTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * @param string $name
     * @param array $data
     * @param string $dataName
     *
     * @return void
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createApplication();
    }

    /**
    * validationProvider
    *
    * @return array
    */
    public function validationProvider()
    {
        $now = Carbon::now();

        return [
            [
                // 必須エラー
                [
                    'branch_name' => '',
                    'todo' => '',
                    'expiration' => ''
                ],
                [
                    0 => 'ブランチ名は必須です',
                    1 => 'TODOリストは必須です',
                    2 => '有効期限は必須です'
                ]
            ],
            [
                // ブランチ名最大255文字エラー
                [
                    'branch_name' => str_repeat('A', '256'),
                    'todo' => 'TODO',
                    'expiration' => $now->format('Y/m/d')
                ],
                [
                    0 => 'ブランチ名は最大255文字です'
                ]
            ],
            [
                // 有効期限日付型エラー
                [
                    'branch_name' => str_repeat('A', '255'),
                    'todo' => 'TODO',
                    'expiration' => $now->format('Y/m/d h')
                ],
                [
                    0 => '有効期限はYYYY/MM/DDの形式で入力してください'
                ]
            ],
            [
                // 有効期限存在しない日付エラー
                [
                    'branch_name' => str_repeat('A', '255'),
                    'todo' => 'TODO',
                    'expiration' => '2021/2/29'
                ],
                [
                    0 => '有効期限はYYYY/MM/DDの形式で入力してください'
                ]
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider validationProvider
     * @return void
     */
    public function TODOバリデーション($assert, $expected)
    {
        try {
            (new Problem())->validateTasks($assert);
        } catch (ValidationException $e) {
            $this->assertSame($expected, $e->error);
        }
    }

    /**
     *
     * @test
     * @return void
     */
    public function タスクごとの全TODO完了処理()
    {
        $tasks = DB::select('
            select T.id, T.name, T.progress, T.num_finished, count(P.id) as cnt
            from tasks T
            join problems P
            on T.id = P.task_id
            group by T.id
        ');

        DB::beginTransaction();

        foreach ($tasks as $task) {
            (new Task())->updateAllTodo($task);
        }

        $tasks = DB::select('
            select progress
            from tasks
            where num_remaining = 0
        ');

        foreach ($tasks as $task) {
            $this->assertSame('100.00', $task->progress);
        }

        DB::rollBack();
    }
}
