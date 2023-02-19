<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use HasFactory, SoftDeletes;

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function directorio()
    {
        return $this->belongsTo(Directorio::class);
    }

    public function ano()
    {
        return $this->belongsTo(Ano::class);
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('documento', 'like', '%'.$search.'%')                      
                      ->orWhereHas('directorio', function ($query) use ($search) {
                        $query->where('nombre_dir', 'like', '%'.$search.'%');
                      })->orWhereHas('ano', function ($query) use ($search) {
                        $query->where('ano', 'like', '%'.$search.'%');
                      })->orWhereHas('mes', function ($query) use ($search) {
                        $query->where('nombre', 'like', '%'.$search.'%');
                      });
            });
        })->when($filters['year'] ?? null, function ($query, $year) {
            $query->where(function ($query) use ($year) {
                $query->where('ano_id', $year);
            });
        })->when($filters['month'] ?? null, function ($query, $month) {
            $query->where(function ($query) use ($month) {
                $query->where('mes_id', $month);
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
