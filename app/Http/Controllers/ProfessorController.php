<?php

namespace App\Http\Controllers;

use App\Models\Eixo;
use App\Models\Professor;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->authorizeResource(Professor::class, 'professor');
    }
    public function index()
    {
        $this->authorize('viewAny',  Professor::class);

        $professores = Professor::all();

        return view('professores.index', compact(['professores']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create',  Professor::class);

        $eixos = Eixo::all();

        return view('professores.create', compact(['eixos']));
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
            'email' => 'required|max:250|min:15|unique:professors',
            'siape' => 'required|max:10|min:8',
            'eixo' => 'required',
            'radio' => 'required',

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
        $this->authorize('create',  Professor::class);

        self::validation($request);

        $eixo = Eixo::find($request->eixo);

        if (isset($eixo)) {

            $obj = new Professor();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->email = mb_strtolower($request->email, 'UTF-8');
            $obj->siape = $request->siape;
            $obj->ativo = $request->radio;
            $obj->eixo()->associate($eixo);

            $obj->save();

            return redirect()->route('professores.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function show(Professor $professor)
    {
        $this->authorize('view', $professor);

        $eixo = Eixo::find($professor->eixo->id);

        if (isset($professor)) {
            $professor->eixo()->associate($eixo);

            return view('professores.show', compact('professor'));
        }

        return "<h1>Professor não Encontrado!</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function edit(Professor $professor)
    {
        $this->authorize('update', $professor);

        return view('professores.edit');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Professor $professor)
    {
        $this->authorize('update', $professor);

        self::validation($request);

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professor $professor)
    {
        $this->authorize('delete', $professor);
    }
}
