<?php

namespace App\Actions\Module;

use App\DTO\Module\ModuleData;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class CreateModule
{
    function __construct(
        private readonly ModuleRepository $moduleRepository,
    ) {}

    public function handle(ModuleData $data): Module
    {
        return DB::transaction(function () use ($data) {
            try {
                $module = $this->moduleRepository->create($data->toArray());

                Log::info('Module created', [
                    'module_id' => $module->id,
                    'company_id' => $module->company_id,
                ]);

                // TODO event?

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
