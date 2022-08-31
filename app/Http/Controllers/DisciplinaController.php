<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Curso;

use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Disciplina::class, 'disciplina');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',  Disciplina::class);

        $disciplinas = Disciplina::all();
        return view('disciplinas.index', compact('disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Disciplina::class);

        $cursos = Curso::orderBy('nome')->get();
        return view('disciplinas.create', compact(['cursos']));
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
            'carga' => 'required|max:12|min:1',
            'curso' => 'required',
        ];
        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($rules, $msgs);
    }
    public function store(Request $request)
    {
        self::validation($request);

        $this->authorize('create',  Disciplina::class);

        $curso = Curso::find($request->curso);

        if (isset($curso)) {
            $obj = new Disciplina();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->carga = $request->carga;
            $obj->curso()->associate($curso);
            $obj->save();

            return redirect()->route('disciplinas.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\Response
     */
    public function show(Disciplina $disciplina)
    {
        
        $this->authorize('view', $disciplina);

        $curso = Curso::find($disciplina->curso->id);

        if (isset($disciplina)) {
            $disciplina->curso()->associate($curso); 

            return view('disciplinas.show', compact('disciplina'));
        }

        return "<h1>Disciplina não Encontrado!</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\Response
     */
    public function edit(Disciplina $disciplina)
    {
        $this->authorize('update', $disciplina);

        $cursos = Curso::all();
        if (isset($disciplina)) {
            return view('disciplinas.edit', compact(['disciplina','cursos']));
        }

        return "<h1>Disciplina não Encontrada!</h1>";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disciplina $disciplina)
    {
        self::validation($request);

        $this->authorize('update', $disciplina);

        if (isset($disciplina)) {
            $disciplina->nome = mb_strtoupper($request->nome, 'UTF-8');
            $disciplina->save();
            return redirect()->route('disciplinas.index');
        }

        return "<h1>Disciplina não Encontrada!</h1>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Disciplina  $disciplina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disciplina $disciplina)
    {
        $this->authorize('delete', $disciplina);

        if (isset($disciplina)) {
            $disciplina->delete();
            return redirect()->route('disciplinas.index');
        }
    }
}
