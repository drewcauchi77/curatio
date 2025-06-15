<?php
// PHPSTAN CONFIRMED
namespace App\Models;

use App\Models\Scopes\ModuleScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

/**
 * Module model.
 * 
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property string $company_id
 * @property int $status_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read string $status_slug
 * @property-read Company $company
 * @property-read Status $status
 * @property-read Collection<int, Course> $courses
 */
class Module extends Model
{
    /** 
     * @use HasFactory<\Database\Factories\ModuleFactory> 
     */
    use HasFactory, HasUuids, ModuleScopes;

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
     * @return BelongsToMany<\App\Models\Course, $this>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Get the company that owns this module.
     *
     * @return BelongsTo<\App\Models\Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the status of this module.
     *
     * @return BelongsTo<\App\Models\Status, $this>
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
