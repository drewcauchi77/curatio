<?php

namespace App\Services\Module;

use App\Models\Module;

class ModuleService
{
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
