<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitorCounter extends Model
{
    protected $table = 'visitor_counters';

    protected $fillable = [
        'key',
        'views_count',
        'last_visit_at',
    ];

    protected $casts = [
        'views_count' => 'integer',
        'last_visit_at' => 'datetime',
    ];

    /**
     * Increment views atomically for a specific key and return the updated count.
     */
    public static function incrementViews(string $key = 'total_website_visits', int $amount = 1): int
    {
        $updated = DB::table('visitor_counters')
            ->where('key', $key)
            ->increment('views_count', $amount, [
                'last_visit_at' => now(),
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            DB::table('visitor_counters')->insert([
                'key' => $key,
                'views_count' => $amount,
                'last_visit_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return $amount;
        }

        return (int) DB::table('visitor_counters')->where('key', $key)->value('views_count');
    }

    /**
     * Get total views for the website.
     */
    public static function getTotalVisits(string $key = 'total_website_visits'): int
    {
        $views = DB::table('visitor_counters')->where('key', $key)->value('views_count');

        if ($views === null) {
            DB::table('visitor_counters')->insert([
                'key' => $key,
                'views_count' => 1,
                'last_visit_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return 1;
        }

        return (int) $views;
    }

    /**
     * Get padded digits array for odometer/flip counter display (e.g. 6 minimum digits).
     *
     * @return array<string>
     */
    public static function getPaddedDigits(int $count, int $minDigits = 6): array
    {
        $str = (string) $count;
        if (strlen($str) < $minDigits) {
            $str = str_pad($str, $minDigits, '0', STR_PAD_LEFT);
        }
        return str_split($str);
    }
}
