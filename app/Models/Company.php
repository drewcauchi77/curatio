<?php
// PHPSTAN CONFIRMED
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

/**
 * Company model.
 * 
 * @property string $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Collection<int, User> $users
 * @property-read Collection<int, Module> $modules
 * @property-read Collection<int, Course> $courses
 */
class Company extends Model
{
    /** 
     * @use HasFactory<\Database\Factories\CompanyFactory> 
     */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get the user records associated with the company.
     *
     * @return HasMany<\App\Models\User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the module records associated with the company.
     *
     * @return HasMany<\App\Models\Module, $this>
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Get the course records associated with the company.
     *
     * @return HasMany<\App\Models\Course, $this>
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
