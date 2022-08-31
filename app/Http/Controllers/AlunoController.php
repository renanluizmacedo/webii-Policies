<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Curso;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Aluno::class, 'aluno');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',  Aluno::class);

        $alunos = Aluno::with(['curso'])->get();

        return view('alunos.index', compact('alunos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create',  Aluno::class);

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
        $this->authorize('create',  Aluno::class);

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
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function show(Aluno $aluno)
    {
        $this->authorize('view', $aluno);

        if (isset($aluno)) {

            $curso = Curso::find($aluno->curso->id);

            if (isset($curso)) {
                $aluno->curso()->associate($curso);
                return view('alunos.show', compact('aluno'));
            } else {
                return redirect()->route('alunos.index');
            }
        }

        return "<h1>Aluno não Encontrado!</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function edit(Aluno $aluno)
    {

        $this->authorize('update', $aluno);

        if (isset($aluno)) {
            $cursos = Curso::all();
            return view('alunos.edit', compact(['aluno', 'cursos']));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aluno $aluno)
    {
        Self::validation($request);

        $this->authorize('update', $aluno);

        if (isset($aluno)) {
            $aluno->nome = mb_strtoupper($request->nome, 'UTF-8');
            $aluno->curso_id = $request->curso;

            $aluno->save();
            return redirect()->route('alunos.index');
        }

        return "<h1>Aluno não Encontrado!</h1>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aluno $aluno)
    {
        $this->authorize('delete', $aluno);

        if (isset($aluno)) {
            $aluno->delete();
            return redirect()->route('alunos.index');
        }
    }
}
