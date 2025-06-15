<?php
// PHPSTAN CONFIRMED
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
     * @param string $title
     * @param string|null $description
     * @param int $statusId
     * @param string|null $companyId
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
     * @param StoreModuleRequest $request
     * @return self
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
     * @param UpdateModuleRequest $request
     * @return self
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
     * @return array<string, string|int|null>
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
     * @return array<string, string|int|null>
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
