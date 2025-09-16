<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    public function socio()
    {
        return $this->belongsTo(Socio::class, 'idsocio');
    }

    public function entrega()
    {
        return $this->belongsTo(Entrega::class, 'identrega');
    }
    protected $guarded = [];
}
