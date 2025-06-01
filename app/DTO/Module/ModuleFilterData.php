<?php

namespace App\DTO\Module;

use Illuminate\Http\Request;

/**
 * Filter criteria for module queries.
 */
final class ModuleFilterData
{
    /**
     * Initialize filter data with search and pagination parameters.
     * 
     * @param string $companyId Company ID to filter by
     * @param string|null $search Search query for title filtering
     * @param string $orderBy Field to order by (default: created_at)
     * @param string $order Sort direction (default: asc)
     * @param string $status Status filter (default: all)
     * @param int $perPage Items per page (default: 12)
     */
    function __construct(
        public readonly string $companyId,
        public readonly ?string $search = null,
        public readonly string $orderBy = 'created_at',
        public readonly string $order = 'asc',
        public readonly string $status = 'all',
        public readonly int $perPage = 12
    ) {}

    /**
     * Create filter data from validated request with user's company.
     * 
     * @param Request $request The incoming HTTP request
     * @return self New instance with request parameters
     */
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
