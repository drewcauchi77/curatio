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
 * 
 * This action handles the update of an existing module with proper error handling,
 * logging, and relationship reloading within a database transaction to ensure
 * data consistency.
 */
final class UpdateModule
{
    /**
     * Initialize action with repository dependency.
     * 
     * @param ModuleRepository $moduleRepository
     * @return void
     */
    function __construct(
        private readonly ModuleRepository $moduleRepository,
    ) {}

    /**
     * Update module attributes and reload its status relationship.
     *
     * This method updates an existing module within a database transaction,
     * logs the update event, reloads the status relationship, and handles
     * any exceptions that may occur during the process.
     *
     * @param Module $module
     * @param ModuleData $data
     * @return Module
     * @throws Exception
     */
    public function handle(Module $module, ModuleData $data): Module
    {
        return DB::transaction(function () use ($module, $data): Module {
            try {
                /** @var Module $module */
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
