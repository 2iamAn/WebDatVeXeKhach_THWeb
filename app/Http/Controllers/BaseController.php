<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

/**
 * Base Controller với các helper methods chung
 */
class BaseController extends Controller
{
    /**
     * Cache key helper để tạo key nhất quán
     */
    protected function cacheKey(string $prefix, ...$params): string
    {
        return $prefix . '_' . implode('_', $params) . '_' . now()->format('Y-m-d-H-i');
    }

    /**
     * Clear cache khi có thay đổi dữ liệu quan trọng
     */
    protected function clearRelatedCache(string $pattern): void
    {
        // Trong production có thể sử dụng Redis tags hoặc cache tags
        // Ở đây chỉ là placeholder, có thể implement sau
    }
}
