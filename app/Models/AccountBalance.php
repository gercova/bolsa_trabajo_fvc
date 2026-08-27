<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    use HasFactory;

    protected $table = 'account_balances';

    protected $fillable = [
        'month',
        'date',
        'receipt_number',
        'client',
        'description',
        'category',
        'program_code',
        'program_name',
        'amount',
        'reason',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    /** Filter by year extracted from the date column. */
    public function scopeFilterByYear(Builder $query, ?int $year): Builder
    {
        if (! $year) {
            return $query;
        }
        return $query->whereYear('date', $year);
    }

    /** Filter by month name (MES column). */
    public function scopeFilterByMonth(Builder $query, ?string $month): Builder
    {
        if (! $month) {
            return $query;
        }
        return $query->where('month', $month);
    }

    /** Filter by category. */
    public function scopeFilterByCategory(Builder $query, ?string $category): Builder
    {
        if (! $category) {
            return $query;
        }
        return $query->where('category', $category);
    }

    /** Filter by program code. */
    public function scopeFilterByProgramCode(Builder $query, ?string $code): Builder
    {
        if (! $code) {
            return $query;
        }
        return $query->where('program_code', $code);
    }

    /** Full-text-style search across client and description. */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($term) {
            $q->where('client', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('receipt_number', 'like', "%{$term}%")
              ->orWhere('reason', 'like', "%{$term}%");
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /** Returns the list of distinct years present in the table. */
    public static function availableYears(): array
    {
        return static::selectRaw('YEAR(date) as year')
            ->whereNotNull('date')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->all();
    }

    /** Returns the list of distinct months present in the table. */
    public static function availableMonths(): array
    {
        return static::select('month')
            ->whereNotNull('month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month')
            ->all();
    }

    /** Returns the list of distinct categories present in the table. */
    public static function availableCategories(): array
    {
        return static::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->all();
    }
}