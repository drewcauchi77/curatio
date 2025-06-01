<?php

namespace App\Actions\Module;

use App\DTO\Module\ModuleData;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Creates a new module within a database transaction.
 */
final class CreateModule
{
    /**
     * Initialize action with repository dependency.
     * 
     * @param ModuleRepository $moduleRepository Repository for module persistence
     */
    function __construct(
        private readonly ModuleRepository $moduleRepository,
    ) {}

    /**
     * Create a module and load its status relationship.
     *
     * @param ModuleData $data Module data to create
     * @return Module The newly created module with status loaded
     * @throws Exception If creation operation fails
     */
    public function handle(ModuleData $data): Module
    {
        return DB::transaction(function () use ($data) {
            try {
                $module = $this->moduleRepository->create($data->toArray());

                Log::info('Module created', [
                    'module_id' => $module->id,
                    'company_id' => $module->company_id,
                ]);

                $module->load('status');

                return $module;
            } catch (Exception $e) {
                Log::error('Failed to create module', [
                    'error' => $e->getMessage(),
                    'data' => $data->toArray(),
                ]);

                throw $e;
            }
        });
    }
}
