<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Problem;
use Faker\Generator as Faker;

$factory->define(Problem::class, function (Faker $faker) {
    return [
        'task_id' => $faker->numberBetween(1, 20),
        'branch_name' => $faker->isbn13(),
        'todo' => $faker->randomElement([
            'どこで手を抜くかを考える',
            'どうやったら早く終わるか考える',
            '何のタスクと一緒にこなすと早く終わるのか整理',
            'なぜ気合いが足りないのか反省文を書く',
            '新人に渡すタスクの割り振り',
            'なぜ進捗が良くないのか書き出す',
            'だめだった項目を書き出す',
            'これから工夫してみたいことは何か考える',
            'どの作業で止まっているのか洗い出す',
            '先方の要望把握',
            'リモートワークでどう言い訳するか考える',
            'みんなのモチベーションが上がる方法を考える',
        ]),
        'expiration' => $faker->dateTime()
    ];
});
