<?php

use App\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Plan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        Plan::insert([
            [
                'nombre'=>'Plan 1 dia',
                'descripcion'=>'Tiempo de almacenamiento por un dia',
                'tiempo'=>86400,
            ],
            [
                'nombre'=>'Plan 1 semana',
                'descripcion'=>'Tiempo de almacenamiento por una semana',
                'tiempo'=>604800,
            ],
            [
                'nombre'=>'Plan 2 semanas',
                'descripcion'=>'Tiempo de almacenamiento por 2 semanas',
                'tiempo'=>(604800*2),
            ],
            [
                'nombre'=>'Plan 1 mes',
                'descripcion'=>'Tiempo de almacenamiento por un mes',
                'tiempo'=>(604800*4),
            ],
            [
                'nombre'=>'Plan 3 mes',
                'descripcion'=>'Tiempo de almacenamiento por 3 meses',
                'tiempo'=>(604800*4*3),
            ],
            [
                'nombre'=>'Plan 6 meses',
                'descripcion'=>'Tiempo de almacenamiento por 6 meses',
                'tiempo'=>(604800*4*6),
            ],
            [
                'nombre'=>'Plan 1 año',
                'descripcion'=>'Tiempo de almacenamiento por un año',
                'tiempo'=>(604800*4*12),
            ],
        ]);
    }
}
