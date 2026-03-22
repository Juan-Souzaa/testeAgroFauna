<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public const LIVRO_CREATED = 'livro.created';

    public const LIVRO_UPDATED = 'livro.updated';

    public const LIVRO_DELETED = 'livro.deleted';

    public const USUARIO_CREATED = 'usuario.created';

    public const USUARIO_ROLE_CHANGED = 'usuario.role_changed';

    public const USUARIO_DELETED = 'usuario.deleted';

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
