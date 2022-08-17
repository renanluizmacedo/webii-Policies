<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Curso;
use App\Models\Docencia;
use App\Facades\UserPermissions;

use Illuminate\Support\Facades\Log;

class DisciplinaController extends Controller
{


    public function index()
    {
        if(!UserPermissions::isAuthorized('disciplinas.index')) {
            return response()->view('templates.restrito');
        }

        $data = Disciplina::with(['curso'])
            ->orderBy('nome')->get();
        // return json_encode($data);
        return view('disciplinas.index', compact(['data']));
    }

    public function create()
    {

        if(!UserPermissions::isAuthorized('disciplinas.create')) {
            return response()->view('templates.restrito');
        }

        $cursos = Curso::orderBy('nome')->get();
        return view('disciplinas.create', compact(['cursos']));
    }

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

    public function show($id)
    {
        if(!UserPermissions::isAuthorized('disciplinas.show')) {
            return response()->view('templates.restrito');
        }
        $doc = Docencia::with(['professor'])
            ->where('disciplina_id', '=', $id)->distinct()->get(['professor_id']);

        return view('disciplinas.show', compact(['doc']));
    }

    public function edit($id)
    {
        if(!UserPermissions::isAuthorized('disciplinas.edit')) {
            return response()->view('templates.restrito');
        }

        $cursos = Curso::orderBy('nome')->get();
        $data = Disciplina::find($id);

        if (isset($data)) {
            return view('disciplinas.edit', compact(['data', 'cursos']));
        }
    }

    public function update(Request $request, $id)
    {

        self::validation($request);


        $curso = Curso::find($request->curso);
        $obj = Disciplina::find($id);

        if (isset($obj) && isset($curso)) {
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->carga = $request->carga;
            $obj->curso()->associate($curso);

            $obj->save();

            return redirect()->route('disciplinas.index');
        }
    }

    public function destroy($id)
    {
        if(!UserPermissions::isAuthorized('disciplinas.destroy')) {
            return response()->view('templates.restrito');
        }
        $obj = Disciplina::find($id);

        if (isset($obj)) {
            $obj->delete();
        }
        return redirect()->route('disciplinas.index');
    }
}
