<?php

namespace App\Models;

use App\Models\Scopes\ModuleScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Module model.
 * 
 * @property string $id Primary UUID
 * @property string $title Module title
 * @property string|null $description Module description
 * @property string $company_id Foreign key to companies
 * @property int $status_id Foreign key to statuses
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read string $status_slug Status slug (draft, published, etc.)
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Status $status
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Course> $courses
 */
class Module extends Model
{
    use HasUuids, ModuleScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'company_id',
        'status_id'
    ];

    /**
     * The attributes that should be eager loaded.
     *
     * @var list<string>
     */
    protected $with = [
        'status'
    ];

    /**
     * The attributes that are appended.
     *
     * @var list<string>
     */
    protected $appends = [
        'status_slug'
    ];

    /**
     * Get the courses that contain this module.
     *
     * @return BelongsToMany<Course, $this>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Get the company that owns this module.
     *
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the status of this module.
     *
     * @return BelongsTo<Status, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the status slug accessor (draft, published, etc.).
     *
     * @return string
     */
    public function getStatusSlugAttribute(): string
    {
        return $this->status->status;
    }
}
