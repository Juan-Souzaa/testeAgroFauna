<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'livros';

    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'preco',
        'categoria_id',
        'publicado_em',
    ];

    protected function casts(): array
    {
        return [
            'publicado_em' => 'date',
            'preco' => 'decimal:2',
        ];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return Carbon::instance($date)->format('Y-m-d');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
