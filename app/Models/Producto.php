<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * Los atributos que se pueden asignar de manera masiva.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Indica si el modelo debe gestionar las marcas de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;
}
