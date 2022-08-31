<?php

namespace App\Http\Controllers;

use App\Models\Eixo;
use Illuminate\Http\Request;

class EixoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->authorizeResource(Eixo::class, 'eixo');
    }
    public function index()
    {
        $this->authorize('viewAny',  Eixo::class);

        $eixos = Eixo::all();
        return view('eixos.index', compact('eixos'));
    }
    public function validation(Request $request)
    {

        $rules = [
            'nome' => 'required|max:100|min:10',
        ];
        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($rules, $msgs);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create',  Eixo::class);

        return view('eixos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        self::validation($request);

        $this->authorize('create',  Eixo::class);

        $obj = new Eixo();
        $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
        $obj->save();

        return redirect()->route('eixos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eixo  $eixo
     * @return \Illuminate\Http\Response
     */
    public function show(Eixo $eixo)
    {
        $this->authorize('view', $eixo);

        if (isset($eixo)) {
            return view('eixos.show', compact('eixo'));
        }

        return "<h1>Eixo não Encontrado!</h1>";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eixo  $eixo
     * @return \Illuminate\Http\Response
     */
    public function edit(Eixo $eixo)
    {
        $this->authorize('update', $eixo);

        if (isset($eixo)) {
            return view('eixos.edit', compact('eixo'));
        }

        return "<h1>Eixo não Encontrado!</h1>";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eixo  $eixo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eixo $eixo)
    {
        self::validation($request);

        $this->authorize('update', $eixo);

        if (isset($eixo)) {
            $eixo->nome = mb_strtoupper($request->nome, 'UTF-8');
            $eixo->save();
            return redirect()->route('eixos.index');
        }

        return "<h1>Eixo não Encontrado!</h1>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eixo  $eixo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eixo $eixo)
    {
        $this->authorize('delete', $eixo);

        if (isset($eixo)) {
            $eixo->delete();
            return redirect()->route('eixos.index');
        }

        return "<h1>Eixo não Encontrado!</h1>";
    }
}
