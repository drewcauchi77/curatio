<?php
// PHPSTAN CONFIRMED
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

/**
 * Course model.
 * 
 * @property string $id
 * @property string $title
 * @property string $company_id
 * @property int|null $status_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Company $company
 * @property-read Status|null $status
 * @property-read Collection<int, Module> $modules
 */
class Course extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'company_id'
    ];

    /**
     * Get modules associated with the course.
     *
     * @return BelongsToMany<\App\Models\Module, $this>
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class);
    }

    /**
     * Get the company that owns this course.
     *
     * @return BelongsTo<\App\Models\Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the status associated with the course.
     *
     * @return BelongsTo<\App\Models\Status, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
