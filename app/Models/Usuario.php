<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory, SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'user_id',
        'telefono',
        'direccion',
    ];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'idUsuario', $value)->withTrashed()->firstOrFail();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereRole($query, $role)
    {
        switch ($role) {
            case 'Usuario': return $query->where('Usuario');
            case 'Administrador': return $query->where('Administrador');
            case 'Colaborador': return $query->where('Colaborador');
            case 'Consultor': return $query->where('Consultor');
            case 'Editor': return $query->where('Editor');
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', '%'.$search.'%')
                      ->orWhere('apellidos', 'like', '%'.$search.'%')
                      ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('username', 'like', '%'.$search.'%')
                              ->orWhere('email', 'like', '%'.$search.'%');
                });
            })->when($filters['role'] ?? null, function ($query, $role) {
                $query->whereRole($role);
            })->when($filters['trashed'] ?? null, function ($query, $trashed) {
                if ($trashed === 'with') {
                    $query->withTrashed();
                } elseif ($trashed === 'only') {
                    $query->onlyTrashed();
                }
            });
        });
    }
}
