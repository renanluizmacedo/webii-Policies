<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disciplina extends Model
{

    protected $fillable = ['nome', 'carga','curso_id'];

    use HasFactory;
    use SoftDeletes;

    public function curso()
    {
        return $this->belongsTo('\App\Models\Curso');
    }


}
