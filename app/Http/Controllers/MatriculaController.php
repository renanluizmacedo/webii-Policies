<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Disciplina;
use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id_aluno = $request->aluno;
        $disciplina = $request->disciplina;


        if (isset($disciplina)) {
            for ($i = 0; $i < count($request->disciplina); $i++) {

                Matricula::where('disciplina_id', '=', $disciplina[$i])->where('aluno_id', '=', $id_aluno)->delete();

                $doc = new Matricula();

                $doc->aluno_id = $id_aluno;
                $doc->disciplina_id = $disciplina[$i];

                $doc->save();
            }
        }


        return redirect()->route('alunos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $disciplinas = Disciplina::with(['curso'])
            ->orderBy('curso_id')->orderBy('id')->get();

        $aluno = Aluno::find($id);

        return view('matriculas.index', compact(['aluno', 'disciplinas']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
