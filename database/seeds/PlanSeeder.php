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
                'descripcion'=>'',
                'tiempo'=>86400,
            ],
            [
                'nombre'=>'Plan 1 semana',
                'descripcion'=>'',
                'tiempo'=>604800,
            ],
            [
                'nombre'=>'Plan 2 semanas',
                'descripcion'=>'',
                'tiempo'=>(604800*2),
            ],
            [
                'nombre'=>'Plan 1 mes',
                'descripcion'=>'',
                'tiempo'=>(604800*4),
            ],
            [
                'nombre'=>'Plan 3 mes',
                'descripcion'=>'',
                'tiempo'=>(604800*4*3),
            ],
            [
                'nombre'=>'Plan 6 meses',
                'descripcion'=>'',
                'tiempo'=>(604800*4*6),
            ],
            [
                'nombre'=>'Plan 1 año',
                'descripcion'=>'',
                'tiempo'=>(604800*4*12),
            ],
        ]);
    }
}
