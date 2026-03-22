<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function registrar(
        User $ator,
        string $acao,
        ?Model $subject = null,
        ?string $descricao = null,
        array $properties = [],
    ): void {
        AuditLog::query()->create([
            'user_id' => $ator->id,
            'action' => $acao,
            'subject_type' => $subject ? $subject->getMorphClass() : null,
            'subject_id' => $subject?->getKey(),
            'description' => $descricao,
            'properties' => $properties === [] ? null : $properties,
        ]);
    }
}
