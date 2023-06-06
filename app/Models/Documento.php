<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Documento extends Model implements Searchable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'documento',
        'directorio_id',
        'ano_id',
        'mes_detalle_id',
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('documentos');

        $fileName = $this->extractFileNameFromDocumento();

        return new SearchResult(
            $this,
            $fileName,
            $url,
        );
    }

    public function extractFileNameFromDocumento(): string
    {
        $documento = json_decode($this->documento);

        $fileName = '';

        if ($documento && isset($documento->name)) {
            $fileName = $documento->name;
        }

        return $fileName;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function directorio()
    {
        return $this->belongsTo(Directorio::class)->withTrashed();
    }

    public function ano()
    {
        return $this->belongsTo(Ano::class)->withTrashed();
    }

    public function mesDetalle()
    {
        return $this->belongsTo(MesDetalle::class)->withTrashed();
    }
    
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('documento', 'like', '%'.$search.'%')
                      ->orWhereHas('directorio', function ($query) use ($search) {
                        $query->where('nombre_dir', 'like', '%'.$search.'%');
                      });
            });
        })->when($filters['year'] ?? null, function ($query, $year) {
            $query->whereHas('ano', function ($query) use ($year) {
                $query->where('id', $year);
            });
        })->when($filters['month'] ?? null, function ($query, $month) {
            $query->whereHas('mesDetalle', function ($query) use ($month) {
                $query->where('id', $month);
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            $trashed === 'only' ? $query->onlyTrashed() : '';
        });
    }
}
