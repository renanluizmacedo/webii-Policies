<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Aluno;
use App\Facades\UserPermissions;

use Illuminate\Http\Request;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!UserPermissions::isAuthorized('alunos.index')) {
            return response()->view('templates.restrito');
        }
        $data = Aluno::with(['curso' => function ($q) {
            $q->withTrashed();
        }])->orderBy('nome')->get();

        return view('alunos.index', compact(['data']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!UserPermissions::isAuthorized('alunos.create')) {
            abort(403);
        }
        $cursos = Curso::orderBy('nome')->get();
        return view('alunos.create', compact(['cursos']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validation(Request $request)
    {

        $rules = [
            'nome' => 'required|max:100|min:10',
            'curso' => 'required',

        ];

        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
            "unique" => "O campo [:attribute] pode ter apenas um único registro!"
        ];

        $request->validate($rules, $msgs);
    }
    public function store(Request $request)
    {
        Self::validation($request);

        $curso = Curso::find($request->curso);

        if (isset($curso)) {

            $obj = new Aluno();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->curso()->associate($curso);

            $obj->save();

            return redirect()->route('alunos.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!UserPermissions::isAuthorized('alunos.edit')) {
            return response()->view('templates.restrito');
        }
        $cursos = Curso::orderBy('nome')->get();
        $data = Aluno::with(['curso' => function ($q) {
            $q->withTrashed();
        }])->find($id);

        if (isset($data)) {
            return view('alunos.edit', compact(['data', 'cursos']));
        }
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
        $rules = [
            'nome' => 'required|max:100|min:10',
            'curso' => 'required',

        ];
        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($rules, $msgs);

        $curso = Curso::find($request->curso);
        $obj_aluno = Aluno::find($id);

        if (isset($curso) && isset($obj_aluno)) {

            $obj_aluno->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj_aluno->curso()->associate($curso);

            $obj_aluno->save();


            return redirect()->route('alunos.index');
        }
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
