<?php

namespace App\Actions\Module;

use App\DTO\Module\ModuleData;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class UpdateModule
{
    function __construct(
        private readonly ModuleRepository $moduleRepository
    ) {}

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
