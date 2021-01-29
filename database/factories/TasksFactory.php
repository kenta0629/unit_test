<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 9),
        'name' => $faker->randomElement([
            '洗濯',
            'トイレ掃除',
            'ゴミ出し',
            'テーブル拭き',
            '給水器掃除',
            '床掃除',
            'ラジオ体操',
            '日直',
            'メール確認',
            '買い出し',
            '業務連絡',
            '査定',
            'MTG',
            'ネットサーフィン',
            '新人教育',
            '先方打ち合わせ',
            '要件決め(オンライン)',
            '単体テスト',
            '結合テスト',
            'KPT'
        ]),
        'progress' => 0,
        'num_remaining' => 1,
        'num_finished' => 0,
    ];
});
