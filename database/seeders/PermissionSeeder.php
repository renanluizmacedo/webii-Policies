<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $arr_cat = ['eixos', 'area', 'alunos', 'professores', 'cursos', 'disciplinas', 'docencias', 'matriculas'];
        $arr_pag = ['index', 'create', 'destroy', 'edit', 'show'];

        for ($i = 1; $i <= 3; $i++) {
            foreach ($arr_cat as $cat) {
                foreach ($arr_pag as $pag) {

                    DB::table('permissions')->insert([
                        'regra' => $cat . '.' . $pag,
                        'permissao' => 1,
                        'type_id' => $i,
                    ]);
                }
            }
        }
    }
}
