<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Status;

class ModuleService
{
    /**
     * @brief   Query modules with search, ordering, and status filters.
     *
     * @param   int    $companyId
     * @param   string $search
     * @param   string $orderBy
     * @param   string $order
     * @param   string $status
     * @param   int    $perPage
     *
     * @return  array
     */
    public function listModules(
        string $companyId,
        string $search = '',
        string $orderBy = 'created_at',
        string $order = 'asc',
        string $status = 'all',
        int $perPage = 10
    ): array {
        $statusIdsBySlug = Status::pluck('id', 'status')->all();

        $draftId     = $statusIdsBySlug['draft'];
        $publishedId = $statusIdsBySlug['published'];
        $deletedId   = $statusIdsBySlug['deleted'];

        $base = Module::query()->where('company_id', $companyId);

        $counts = [
            'drafts'    => (clone $base)->where('status_id', $draftId)->count(),
            'published' => (clone $base)->where('status_id', $publishedId)->count(),
            'trash'     => (clone $base)->where('status_id', $deletedId)->count(),
        ];
        $counts['all'] = $counts['drafts'] + $counts['published'];

        switch ($status) {
            case 'drafts':
                $statusFilter = [$draftId];
                break;
            case 'published':
                $statusFilter = [$publishedId];
                break;
            case 'trash':
                $statusFilter = [$deletedId];
                break;
            default:
                $statusFilter = [$draftId, $publishedId];
        }

        $paginator = $base->whereIn('status_id', $statusFilter)
            ->when($search, fn($query) => $query->where('title', 'LIKE', "%{$search}%"))
            ->when(in_array($order, ['asc', 'desc']), function ($query) use ($orderBy, $order) {
                if ($orderBy === 'title') {
                    $query->orderByRaw('LOWER(title) ' . $order);
                } else {
                    $query->orderBy($orderBy, $order);
                }
            })
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn($module) => [
                'id'            => $module->id,
                'title'         => $module->title,
                'description'   => substr($module->description, 0, 100),
                'status'        => $module->status->status,
            ]);

        return compact('paginator', 'counts');
    }

    /**
     * @brief   Create a module.
     *
     * @param   array $attributes
     * 
     * @return  \App\Models\Module
     */
    public function createModule(array $attributes): Module
    {
        return Module::create($attributes);
    }

    /**
     * @brief   Update an existing module.
     *
     * @param   \App\Models\Module $module
     * @param   array $attributes
     * 
     * @return  \App\Models\Module
     */
    public function updateModule(Module $module, array $attributes): Module
    {
        $module->update($attributes);
        return $module;
    }
}
