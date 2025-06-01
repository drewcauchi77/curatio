<?php

namespace App\Actions\Module;

use App\DTO\Module\ModuleData;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Updates an existing module within a database transaction.
 */
final class UpdateModule
{
    /**
     * Initialize action with repository dependency.
     * 
     * @param ModuleRepository $moduleRepository Repository for module persistence
     */
    function __construct(
        private readonly ModuleRepository $moduleRepository
    ) {}

    /**
     * Update module attributes and reload its status relationship.
     *
     * @param Module $module The module to update
     * @param ModuleData $data New module data to apply
     * @return Module The updated module with fresh status
     * @throws Exception If update operation fails
     */
    public function handle(Module $module, ModuleData $data): Module
    {
        return DB::transaction(function () use ($module, $data) {
            try {
                $module = $this->moduleRepository->update($module, $data->toArrayWithoutCompany());

                // TODO Which changes?
                Log::info('Module updated', [
                    'module_id' => $module->id,
                    'company_id' => $module->company_id,
                ]);

                // TODO event?
                $module->load('status');

                return $module;
            } catch (Exception $e) {
                Log::error('Failed to update module', [
                    'error' => $e->getMessage(),
                    'module_id' => $module->id,
                ]);

                throw $e;
            }
        });
    }
}
