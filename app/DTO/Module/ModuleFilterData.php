<?php

namespace App\DTO\Module;

use Illuminate\Http\Request;

final class ModuleFilterData
{
    function __construct(
        public readonly string $companyId,
        public readonly ?string $search = null,
        public readonly string $orderBy = 'created_at',
        public readonly string $order = 'asc',
        public readonly string $status = 'all',
        public readonly int $perPage = 12
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            companyId: $request->user()->company_id,
            search: $request->validated('q'),
            orderBy: $request->validated('orderBy', 'created_at'),
            order: $request->validated('order', 'asc'),
            status: $request->validated('status', 'all'),
            perPage: 12
        );
    }
}
