<?php

namespace App\DTO\Module;

use App\Http\Requests\Module\StoreModuleRequest;
use App\Http\Requests\Module\UpdateModuleRequest;

final class ModuleData
{
    function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly int $statusId,
        public readonly ?string $companyId = null,
    ) {}

    public static function fromStoreRequest(StoreModuleRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            statusId: $request->validated('status_id'),
            companyId: $request->user()->company_id,
        );
    }

    public static function fromUpdateRequest(UpdateModuleRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            statusId: $request->validated('status_id'),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->statusId,
            'company_id' => $this->companyId,
        ];
    }

    public function toArrayWithoutCompany(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status_id' => $this->statusId,
        ];
    }
}
