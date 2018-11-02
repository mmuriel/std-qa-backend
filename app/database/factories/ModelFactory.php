<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


 
// tipo datos para reportes
$factory->define(Siba\QA\Reporte\Reporte::class, function (Faker\Generator $faker) {
    return [
        'idmd5' => $faker->md5,
        'usuario' => 'mmuriel@siba.com.co',
        'tipo' => $faker->randomElement(array (0,1)),
        'evento'=> $faker->numberBetween(267439966,360162877),
        'canal'=> $faker->numberBetween(380,1100),
        'evento_titulo'=> 'XXXXXXXX YYYYYYYYYYY',
        'evento_fechahora'=> $faker->date($format = 'Y-m-d H:i:s', $max = 'now')
    ];
});


// tipo datos para errores
$factory->define(Siba\QA\Error\Error::class, function (Faker\Generator $faker) {
    return [
        
        'idmd5'=>$faker->md5,
        'tipo'=> $faker->randomElement(array (1,2,3)),
        'motivo'=>$faker->randomElement(array (1,2,3)),
        'detalle'=>$faker->realText(80,0),
        'desfase'=>$faker->numberBetween(1,60),
        'transmitiendo'=>$faker->realText(50,1),
        'reporte' => function() {
            return factory(Siba\QA\Reporte::class)->create()->id;
        },
    ];
});