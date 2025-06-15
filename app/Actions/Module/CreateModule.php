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
 * 
 * This action handles the creation of a new module with proper error handling,
 * logging, and relationship loading within a database transaction to ensure
 * data consistency.
 */
final class CreateModule
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
     * Create a module and load its status relationship.
     *
     * This method creates a new module within a database transaction,
     * logs the creation event, loads the status relationship, and handles
     * any exceptions that may occur during the process.
     *
     * @param ModuleData $data
     * @return Module
     * @throws Exception
     */
    public function handle(ModuleData $data): Module
    {
        return DB::transaction(function () use ($data): Module {
            try {
                /** @var Module $module */
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
