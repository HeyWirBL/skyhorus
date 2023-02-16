<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meses';

    /**
     * Get the meses_detalles associated with the mes.
     */
    public function meses_detalles()
    {
        return $this->hasOne(MesesDetalle::class);
    }
}
