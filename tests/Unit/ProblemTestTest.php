<?php

namespace Tests\Unit;

use App\Exceptions\ValidationException;
use App\Model\Problem;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class ProblemTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->createApplication();
        $this->problem = new Problem();
    }

    /**
    * validationProvider
    *
    * @return array
    */
    public function validationProvider(): array
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
     * TODOを新規で作成した際のバリデーションテスト
     *
     * @test
     * @dataProvider validationProvider
     * @param array $assert テストパターン
     * @param array $expected 期待値
     * @return void
     */
    public function TODOバリデーション(array $assert, array $expected): void
    {
        try {
            $this->problem->validateTasks($assert);
        } catch (ValidationException $e) {
            $this->assertSame($expected, $e->error);
        }
    }

    /**
     * タスクごとのTODOをそれぞれ全部完了の状態にするテスト
     *
     * @test
     * @return void
     */
    public function タスクごとの全TODO完了処理(): void
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
            $this->problem->updateAllTodo($task);
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

    /**
     *「どこで手を抜くかを考える」のTODOを
     *「どうやって手を抜くか」に変更できたかのテスト
     *
     * @test
     * @return void
     */
    public function TODOの内容変更(): void
    {
        DB::beginTransaction();

        $this->problem->updateTodoDetail('どうやって手を抜くか');

        $todo_lists = DB::select('
            select count(*) as cnt
            from problems
            where todo = :todo
        ', ['todo' => 'どこで手を抜くかを考える']);

        $this->assertSame(0, $todo_lists[0]->cnt);

        DB::rollBack();
    }
}
