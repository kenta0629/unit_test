<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([
            'はぴたすさん',
            'ゆーたろーさん',
            'ぺえ作さん',
            'ゆうたそさん',
            'RYOTAさん',
            'ダンさん',
            '壮さん',
            'としさん',
        ]),
        'strengths' => $faker->randomElement([
            '朝、受付でお姉さんと話すと忍耐力2、活力5UP',
            '勤続3日目は特に追加で3時間働きたくなってくる、社畜の呼吸！',
            '小さいことでも幸せを感じる、ちなみに今日は四葉のクローバーを見つけた！',
            '飲み会があると知ってから素速さ10UP',
            'ぼくと言ったら檸檬堂、誰にも負けない帰宅スピード！！',
            'プログラミングの知識量なら負けない！',
            '設計、開発、保守の全フロー経験あり！',
            'いくら徹夜してもコードなら永遠に書いてられる',
            '根拠のない自信で困難を乗り越える',
        ]),
        'weekness' => $faker->randomElement([
            'プログラミングを見ると吐き気がしてくる',
            'テストを書くのがめっぽう苦手',
            '酒飲むとだいたい2日酔で仕事にならない',
            '活字が苦手',
            '朝早く起きられない',
            '数字が苦手',
            '人のコードを読むのが苦手',
            '酒を飲んでないと息切れしてしまう',
            '午前中は集中力が保てない',
        ])
    ];
});
