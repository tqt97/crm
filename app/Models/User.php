<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Database\Factories\UserFactory;
use App\Models\Department;
use App\Traits\ScopeEloquent;
use App\Traits\SpatieLogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /**
     *
     * @use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;
    use HasPermissions;
    use SoftDeletes;
    use SpatieLogsActivity;
    use ScopeEloquent;

    protected $guard_name = "api";
    protected $timezone;

    protected $fillable = [
        'email',
        'password',
        'remember_token',
        'status',
        'deleted_at',
        'deleted_by',
        'uuid',
        'type',
        'employee_code',
        'status',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'avatar_url',
        'gender',
        'identity_number',
        'language',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
        );
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->timezone = Arr::get(Auth::user(), 'timezone', config('app.timezone'));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Set format & value for the attributes
     */
    public function setAttribute($key, $value)
    {
        // set timezone to UTC before saving db
        if ($this->isDateTimeCast($key)) {
            $value = Carbon::parse($value)->tz('UTC');
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Convert format & value for the attributes
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // set timezone to UTC before showing
        if ($this->isDateTimeCast($key)) {
            return Carbon::parse($value)->tz($this->timezone)->format('Y-m-d H:i:s');
        }

        return $value;
    }

    /**
     * Checking Datetime
     */
    protected function isDateTimeCast($key)
    {
        return array_key_exists($key, $this->getCasts()) && Arr::get($this->getCasts(), $key) === 'datetime';
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // updating created_by and updated_by when model is created
        static::creating(
            function ($model) {
                if (Schema::hasColumn($model->getTable(), 'created_by') && !$model->isDirty('created_by')) {
                    $model->created_by = Auth::id() ?? 0;
                }
                if (Schema::hasColumn($model->getTable(), 'updated_by') && !$model->isDirty('updated_by')) {
                    $model->updated_by = Auth::id() ?? 0;
                }
                if (Schema::hasColumn($model->getTable(), 'uuid')) {
                    $model->uuid = Str::uuid()->toString();
                }
            }
        );

        // updating updated_by when model is updated
        static::updating(
            function ($model) {
                if (Schema::hasColumn($model->getTable(), 'updated_by') && !$model->isDirty('updated_by')) {
                    $model->updated_by = Auth::id() ?? 0;
                }
            }
        );

        // updating deleted_by when model is deleted
        static::deleting(
            function ($model) {
                if (Schema::hasColumn($model->getTable(), 'deleted_by') && !$model->isDirty('deleted_by')) {
                    $model->deleted_by = Auth::id() ?? 0;
                }
            }
        );
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'user_positions')
        ->using(UserPosition::class)
        ->withPivot('department_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'user_positions')
            ->withPivot('department_id');
    }

    /**
     * Get the assignments for the user.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Scope Filter
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return void
     */
    public function scopeFilter($query, $request)
    {
        if ($request->filled('name')) {
            $searchName = str_replace(' ', '', $request->input('name'));
            $query->whereRaw("REPLACE(CONCAT(first_name, '', last_name), ' ', '') LIKE ?", ["%{$searchName}%"])
                ->orWhere('employee_code', 'LIKE', '%' . $searchName . '%');
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('department_ids')) {
            $selectDepartment = $request->input('department_ids');
            $selectDepartments = is_array($selectDepartment) ? $selectDepartment : [$selectDepartment];
            $query->whereHas('departments', function ($q) use ($selectDepartments) {
                $q->whereIn('department_id', $selectDepartments);
            });
        }

        return $query;
    }
}
