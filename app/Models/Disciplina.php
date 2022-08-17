<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disciplina extends Model
{

    protected $fillable = ['nome', 'sigla', 'tempo', 'eixo_id'];

    use HasFactory;
    use SoftDeletes;

    public function curso()
    {
        return $this->belongsTo('\App\Models\Curso');
    }
    public function area()
    {
        return $this->belongsTo('\App\Models\Area');
    }
    public function professor()
    {
        return $this->belongsTo('\App\Models\Professor');
    }
    public function aluno()
    {
        return $this->hasMany('\App\Models\Aluno');
    }
}
