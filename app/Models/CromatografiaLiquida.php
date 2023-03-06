<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CromatografiaLiquida extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'documento',
        'pozo_id',
        'fecha_hora',
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
                $query->where('documento', 'like', '%'.$search.'%')                      
                      ->orWhereHas('pozo', function ($query) use ($search) {
                        $query->where('nombre_pozo', 'like', '%'.$search.'%');
                    });
            });
        })->when($filters['year'] ?? null, function ($query, $year) {
            $query->whereYear('fecha_hora', $year);
        })->when($filters['month'] ?? null, function ($query, $month) {
            $query->whereMonth('fecha_hora', $month);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            $trashed === 'only' ? $query->onlyTrashed() : '';
        });
    }
}
