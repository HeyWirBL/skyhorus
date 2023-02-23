<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pozo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'punto_muestreo',
        'fecha_hora',
        'identificador',
        'presion_kgcm2',
        'presion_psi',
        'temp_C',
        'temp_F',
        'volumen_cm3',
        'volumen_lts',
        'observaciones',
        'nombre_pozo',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function docPozos()
    {
        return $this->hasMany(DocPozo::class);
    }

    public function componentePozos()
    {
        return $this->hasMany(ComponentePozo::class);
    }

    public function cromatografiaGases()
    {
        return $this->hasMany(CromatografiaGas::class);
    }

    public function cromatografiaLiquidas()
    {
        return $this->hasMany(CromatografiaLiquida::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('punto_muestreo', 'like', '%'.$search.'%')
                  ->orWhere('identificador', 'like', '%'.$search.'%')
                  ->orWhere('nombre_pozo', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
