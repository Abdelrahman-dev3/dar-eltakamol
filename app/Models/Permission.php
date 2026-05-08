<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * Get the categories that have this permission.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_permission')
            ->withTimestamps();
    }

    /**
     * Get the departments that have this permission.
     */
    public function departments(): BelongsToMany
    {
        return $this->categories()->whereNotNull('categories.parent_id');
    }

    /**
     * The users that have this permission directly assigned.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permission_user')
            ->withTimestamps();
    }

    /**
     * Backward-compatible slug attribute for legacy UI.
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->name ?: 'permission', '.');
    }

    /**
     * Backward-compatible description attribute for legacy UI.
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->attributes['description'] ?? null;
    }

    /**
     * Backward-compatible module attribute for legacy UI.
     */
    public function getModuleAttribute(): ?string
    {
        if (array_key_exists('module', $this->attributes)) {
            return $this->attributes['module'];
        }

        $parts = $this->permissionParts();
        if (count($parts) <= 1) {
            return null;
        }

        array_pop($parts);

        return implode('.', $parts);
    }

    /**
     * Human-friendly Arabic label for the permission.
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = $this->permissionParts();

        if (empty($parts)) {
            return (string) $this->name;
        }

        if (count($parts) === 1) {
            return $this->translateToken($parts[0]);
        }

        $action = array_pop($parts);
        $resourceKey = implode('.', $parts);
        $resourceLabel = $this->translateResource($resourceKey);
        $actionLabel = $this->translateAction($action);

        if ($resourceLabel && $actionLabel) {
            return trim($actionLabel . ' ' . $resourceLabel);
        }

        if ($resourceLabel) {
            return $resourceLabel;
        }

        return $this->translateLoosePermissionName((string) $this->name);
    }

    /**
     * Human-friendly Arabic label for the permission module/resource.
     */
    public function getModuleDisplayAttribute(): string
    {
        $module = $this->module;

        if (!$module) {
            return 'عام';
        }

        return $this->translateResource($module);
    }

    private function permissionParts(): array
    {
        $normalized = Str::of((string) $this->name)
            ->replace([':', '/', '\\'], '.')
            ->replace('-', '_')
            ->lower()
            ->value();

        return array_values(array_filter(explode('.', $normalized), fn ($part) => $part !== ''));
    }

    private function translateLoosePermissionName(string $name): string
    {
        $tokens = preg_split('/[.\:_\-]+/', Str::lower($name)) ?: [];
        $translated = array_map(fn ($token) => $this->translateToken($token), array_filter($tokens));

        return !empty($translated) ? implode(' ', $translated) : $name;
    }

    private function translateToken(string $token): string
    {
        $translatedAction = $this->translateAction($token, false);
        if ($translatedAction !== null) {
            return $translatedAction;
        }

        return $this->translateResource($token);
    }

    private function translateAction(string $action, bool $fallback = true): ?string
    {
        $map = [
            'view' => 'عرض',
            'index' => 'عرض',
            'show' => 'عرض التفاصيل',
            'create' => 'إضافة',
            'store' => 'حفظ',
            'edit' => 'تعديل',
            'update' => 'تحديث',
            'delete' => 'حذف',
            'destroy' => 'حذف',
            'download' => 'تنزيل',
            'print' => 'طباعة',
            'vote' => 'تصويت',
            'results' => 'عرض النتائج',
            'toggle' => 'تغيير الحالة',
            'attach' => 'ربط',
            'detach' => 'فصل',
            'upload' => 'رفع',
            'export' => 'تصدير',
            'import' => 'استيراد',
            'approve' => 'اعتماد',
            'reject' => 'رفض',
            'manage' => 'إدارة',
            'assign' => 'تعيين',
            'post' => 'ترحيل',
            'mark' => 'تحديد',
            'paid' => 'تحصيل',
        ];

        if (array_key_exists($action, $map)) {
            return $map[$action];
        }

        return $fallback ? $this->humanizeToken($action) : null;
    }

    private function translateResource(string $resource): string
    {
        $map = [
            'contributors' => 'المساهمين',
            'contributors.documents' => 'مستندات المساهمين',
            'users' => 'المستخدمين',
            'permissions' => 'الصلاحيات',
            'meetings' => 'الاجتماعات',
            'meetings.attachments' => 'مرفقات الاجتماعات',
            'polls' => 'الاستطلاعات',
            'poll.options' => 'خيارات الاستطلاع',
            'poll.answers' => 'التصويتات',
            'poll-options' => 'خيارات الاستطلاع',
            'poll-answers' => 'التصويتات',
            'shares' => 'الأسهم',
            'sell.shares' => 'بيع الأسهم',
            'sell-shares' => 'بيع الأسهم',
            'shares.trans' => 'تحويلات الأسهم',
            'shares-trans' => 'تحويلات الأسهم',
            'shares.pos' => 'طلبات الأسهم',
            'shares-pos' => 'طلبات الأسهم',
            'share.trans.lines' => 'بنود التحويل',
            'share-trans-lines' => 'بنود التحويل',
            'transactions' => 'المعاملات',
            'payments' => 'المدفوعات',
            'documents' => 'الملفات',
            'regulations' => 'اللوائح',
            'circulars' => 'التعاميم',
            'categories' => 'العضويات',
            'category' => 'العضويات',
            'bookings' => 'الحجوزات',
            'services' => 'الخدمات',
            'servies' => 'الخدمات',
            'settings' => 'الإعدادات',
            'profile' => 'الملف الشخصي',
            'profits' => 'الأرباح',
            'users.profits' => 'أرباح المستخدمين',
            'users-profits' => 'أرباح المستخدمين',
            'dashboard' => 'لوحة التحكم',
            'modify' => 'التعديلات',
            'general' => 'عام',
        ];

        if (array_key_exists($resource, $map)) {
            return $map[$resource];
        }

        $segments = preg_split('/[.\-_]+/', $resource) ?: [];
        $translated = array_map(fn ($segment) => $this->humanizeToken($segment), array_filter($segments));

        return !empty($translated) ? implode(' ', $translated) : $resource;
    }

    private function humanizeToken(string $token): string
    {
        $token = trim(Str::lower($token));

        $map = [
            'app' => 'التطبيق',
            'user' => 'مستخدم',
            'users' => 'المستخدمين',
            'permission' => 'صلاحية',
            'permissions' => 'الصلاحيات',
            'booking' => 'حجز',
            'bookings' => 'الحجوزات',
            'meeting' => 'اجتماع',
            'meetings' => 'الاجتماعات',
            'attachment' => 'مرفق',
            'attachments' => 'المرفقات',
            'document' => 'ملف',
            'documents' => 'الملفات',
            'regulation' => 'لائحة',
            'regulations' => 'اللوائح',
            'circular' => 'تعميم',
            'circulars' => 'التعاميم',
            'category' => 'عضوية',
            'categories' => 'العضويات',
            'service' => 'خدمة',
            'services' => 'الخدمات',
            'setting' => 'إعداد',
            'settings' => 'الإعدادات',
            'share' => 'سهم',
            'shares' => 'الأسهم',
            'transaction' => 'معاملة',
            'transactions' => 'المعاملات',
            'profit' => 'ربح',
            'profits' => 'الأرباح',
            'payment' => 'دفعة',
            'payments' => 'المدفوعات',
            'poll' => 'استطلاع',
            'polls' => 'الاستطلاعات',
            'option' => 'خيار',
            'options' => 'الخيارات',
            'answer' => 'تصويت',
            'answers' => 'التصويتات',
            'profile' => 'الملف الشخصي',
        ];

        return $map[$token] ?? Str::headline($token);
    }
}
