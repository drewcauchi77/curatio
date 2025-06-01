<?php

namespace App\DTO\Module;

use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;

/**
 * Data transfer object for module attributes.
 */
final class ModuleData
{
    /**
     * Initialize module data.
     * 
     * @param string $title Module title
     * @param string|null $description Module description
     * @param int $statusId Status ID reference
     * @param string|null $companyId Company ID (optional)
     */
    function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly int $statusId,
        public readonly ?string $companyId = null,
    ) {}

    /**
     * Create from store request with user's company ID.
     * 
     * @param StoreModuleRequest $request Validated store request
     * @return self New instance with request data and company ID
     */
    public static function fromStoreRequest(StoreModuleRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            statusId: $request->validated('status_id'),
            companyId: $request->user()->company_id,
        );
    }

    /**
     * Create from update request without company ID.
     * 
     * @param UpdateModuleRequest $request Validated update request
     * @return self New instance with request data only
     */
    public static function fromUpdateRequest(UpdateModuleRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            statusId: $request->validated('status_id'),
        );
    }

    /**
     * Convert to array with all properties.
     * 
     * @return array Array with title, description, status_id, and company_id
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->statusId,
            'company_id' => $this->companyId,
        ];
    }

    /**
     * Convert to array excluding company_id.
     * 
     * @return array Array with title, description, and status_id only
     */
    public function toArrayWithoutCompany(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->statusId,
        ];
    }
}
