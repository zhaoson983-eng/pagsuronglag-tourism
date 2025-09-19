<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusinessStatistic extends Model
{
    protected $fillable = [
        'business_profile_id',
        'date',
        'page_views',
        'unique_visitors',
        'product_views',
        'product_clicks',
        'orders_received',
        'revenue',
        'average_order_value',
        'conversion_rate',
        'new_customers',
        'returning_customers'
    ];

    protected $casts = [
        'date' => 'date',
        'page_views' => 'integer',
        'unique_visitors' => 'integer',
        'product_views' => 'integer',
        'product_clicks' => 'integer',
        'orders_received' => 'integer',
        'revenue' => 'decimal:2',
        'average_order_value' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'new_customers' => 'integer',
        'returning_customers' => 'integer'
    ];

    // Relationships
    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    // Scopes
    public function scopeForDateRange($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?: $startDate;
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeThisYear($query)
    {
        return $query->whereBetween('date', [
            now()->startOfYear(),
            now()->endOfYear()
        ]);
    }

    // Statistics Recording Methods
    public static function recordPageView(BusinessProfile $business)
    {
        return static::updateOrCreate(
            [
                'business_profile_id' => $business->id,
                'date' => now()->toDateString()
            ],
            [
                'page_views' => DB::raw('COALESCE(page_views, 0) + 1')
            ]
        );
    }

    public static function recordProductView(BusinessProfile $business, $productId)
    {
        return static::updateOrCreate(
            [
                'business_profile_id' => $business->id,
                'date' => now()->toDateString()
            ],
            [
                'product_views' => DB::raw('COALESCE(product_views, 0) + 1')
            ]
        );
    }

    public static function recordOrder(BusinessProfile $business, $amount, $isNewCustomer = false)
    {
        $stat = static::firstOrCreate(
            [
                'business_profile_id' => $business->id,
                'date' => now()->toDateString()
            ]
        );

        $stat->increment('orders_received');
        $stat->increment('revenue', $amount);
        
        if ($isNewCustomer) {
            $stat->increment('new_customers');
        } else {
            $stat->increment('returning_customers');
        }

        // Recalculate average order value
        $stat->average_order_value = $stat->revenue / $stat->orders_received;
        
        // Recalculate conversion rate (simplified)
        if ($stat->page_views > 0) {
            $stat->conversion_rate = ($stat->orders_received / $stat->page_views) * 100;
        }
        
        $stat->save();
        
        return $stat;
    }

    // Reporting Methods
    public static function getSummary(BusinessProfile $business, $startDate, $endDate = null)
    {
        $endDate = $endDate ?: $startDate;
        
        return $business->statistics()
            ->select([
                DB::raw('SUM(page_views) as total_page_views'),
                DB::raw('SUM(unique_visitors) as total_unique_visitors'),
                DB::raw('SUM(product_views) as total_product_views'),
                DB::raw('SUM(orders_received) as total_orders'),
                DB::raw('SUM(revenue) as total_revenue'),
                DB::raw('AVG(average_order_value) as avg_order_value'),
                DB::raw('AVG(conversion_rate) as avg_conversion_rate'),
                DB::raw('SUM(new_customers) as new_customers'),
                DB::raw('SUM(returning_customers) as returning_customers')
            ])
            ->whereBetween('date', [$startDate, $endDate])
            ->first();
    }

    public static function getTrends(BusinessProfile $business, $period = 'monthly', $limit = 12)
    {
        $groupBy = '';
        $dateFormat = '';
        
        switch (strtolower($period)) {
            case 'daily':
                $groupBy = 'DATE(date)';
                $dateFormat = '%Y-%m-%d';
                $subDays = $limit;
                break;
            case 'weekly':
                $groupBy = 'YEARWEEK(date, 1)';
                $dateFormat = '%x-W%v';
                $subWeeks = $limit;
                break;
            case 'monthly':
            default:
                $groupBy = 'DATE_FORMAT(date, "%Y-%m")';
                $dateFormat = '%Y-%m';
                $subMonths = $limit;
                break;
        }
        
        return $business->statistics()
            ->select([
                DB::raw('DATE_FORMAT(date, "' . $dateFormat . '") as period'),
                DB::raw('SUM(page_views) as page_views'),
                DB::raw('SUM(unique_visitors) as unique_visitors'),
                DB::raw('SUM(orders_received) as orders'),
                DB::raw('SUM(revenue) as revenue'),
                DB::raw('AVG(conversion_rate) as conversion_rate')
            ])
            ->where('date', '>=', now()->sub($period, $limit - 1)->startOf($period))
            ->groupBy(DB::raw($groupBy))
            ->orderBy('period')
            ->get();
    }

    public static function getTopProducts(BusinessProfile $business, $startDate, $endDate = null, $limit = 5)
    {
        // This is a placeholder - you would need to implement this based on your product views/orders
        return collect();
    }

    // Helper Methods
    public function getRevenueFormattedAttribute()
    {
        return '₱' . number_format($this->revenue, 2);
    }

    public function getConversionRateFormattedAttribute()
    {
        return number_format($this->conversion_rate, 2) . '%';
    }

    public function getAverageOrderValueFormattedAttribute()
    {
        return '₱' . number_format($this->average_order_value, 2);
    }
}
