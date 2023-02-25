<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComponentePozo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dioxido_carbono',
        'pe_dioxido_carbono',
        'mo_dioxido_carbono',
        'den_dioxido_carbono',
        'acido_sulfidrico',
        'pe_acido_sulfidrico',
        'mo_acido_sulfidrico',
        'den_acido_sulfidrico',
        'nitrogeno',
        'pe_nitrogeno',
        'mo_nitrogeno',
        'den_nitrogeno',
        'metano',
        'pe_metano',
        'mo_metano',
        'den_metano',
        'etano',
        'pe_etano',
        'mo_etano',
        'den_etano',
        'propano',
        'pe_propano',
        'mo_propano',
        'den_propano',
        'iso_butano',
        'pe_iso_butano',
        'mo_iso_butano',
        'den_iso_butano',
        'n_butano',
        'pe_n_butano',
        'mo_n_butano',
        'den_n_butano',
        'iso_pentano',
        'pe_iso_pentano',
        'mo_iso_pentano',
        'den_iso_pentano',
        'n_pentano',
        'pe_n_pentano',
        'mo_n_pentano',
        'den_n_pentano',
        'n_exano',
        'pe_n_exano',
        'mo_n_exano',
        'den_n_exano',
        'pozo_id',
        'fecha_recep',
        'fecha_analisis',
        'no_determinacion',
        'equipo_utilizado',
        'met_laboratorio',
        'observaciones',
        'nombre_componente',
        'fecha_muestreo',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function pozo()
    {
        return $this->belongsTo(Pozo::class)->withTrashed();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('equipo_utilizado', 'like', '%'.$search.'%')
                      ->orWhere('nombre_componente', 'like', '%'.$search.'%')
                      ->orWhereHas('pozo', function ($query) use ($search) {
                        $query->where('nombre_pozo', 'like', '%'.$search.'%');
                    });
            });                              
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            $trashed === 'only' ? $query->onlyTrashed() : '';
        });
    }    
}
