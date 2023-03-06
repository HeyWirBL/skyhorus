<?php

namespace App\Exports;

use App\Models\ComponentePozo;
use Maatwebsite\Excel\Concerns\FromCollection;

class ComponentePozosExport implements FromCollection
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ComponentePozo::all();
    }
}
