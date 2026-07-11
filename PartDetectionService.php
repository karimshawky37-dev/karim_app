<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class PartDetectionService
{
    private PDO $db;

    // ✅ قائمة الكلمات المفتاحية للقطع حسب الفئة
    private array $partKeywords = [
        'شاشة' => 'شاشة',
        'بطارية' => 'بطارية',
        'سماعة' => 'سماعة',
        'ميكرفون' => 'سماعة',
        'صوت' => 'سماعة',
        'شاحن' => 'شاحن',
        'كابل' => 'كابل',
        'بوردة' => 'بوردة',
        'ماين بورد' => 'بوردة',
        'ic' => 'IC',
        'ايسي' => 'IC',
        'زجاج' => 'زجاج',
        'بصمة' => 'بصمة',
        'فينجر' => 'بصمة',
        'كاميرا' => 'كاميرا',
        'لينس' => 'كاميرا',
        'فلاش' => 'فلاش',
        'زر' => 'أزرار',
        'باور' => 'أزرار',
        'طاقة' => 'أزرار',
        'هاوس' => 'أزرار',
        'هوم' => 'أزرار',
    ];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * تحليل العطل واستخراج القطعة المطلوبة
     */
    public function detectPart(string $issue, string $brand = '', string $model = ''): ?array
    {
        $issue = mb_strtolower($issue);
        $detectedCategory = null;
        $detectedKeyword = null;

        // 1. البحث عن الكلمات المفتاحية في العطل
        foreach ($this->partKeywords as $keyword => $category) {
            if (mb_strpos($issue, $keyword) !== false) {
                $detectedCategory = $category;
                $detectedKeyword = $keyword;
                break;
            }
        }

        // لو مفيش كلمة مفتاحية، نرجع null (يعني العطل مش محتاج قطعة)
        if (!$detectedCategory) {
            return null;
        }

        // 2. البحث في المخزون عن قطعة تناسب (نستخدم اسم القطعة أو الفئة)
        $searchTerms = [];

        // أضف اسم الماركة لو موجودة
        if (!empty($brand)) {
            $searchTerms[] = trim($brand);
        }

        // أضف اسم الموديل لو موجود
        if (!empty($model)) {
            $searchTerms[] = trim($model);
        }

        // أضف الفئة والكلمة المفتاحية
        $searchTerms[] = $detectedCategory;
        $searchTerms[] = $detectedKeyword;

        // 3. بناء استعلام البحث
        $sql = "
            SELECT id, name, current_quantity, selling_price 
            FROM inventory 
            WHERE current_quantity > 0 
              AND deleted_at IS NULL
              AND (
        ";

        $conditions = [];
        $params = [];

        // نبحث في الاسم عن أي من الكلمات
        foreach ($searchTerms as $term) {
            if (strlen($term) < 2) continue;
            $conditions[] = "LOWER(name) LIKE LOWER(?)";
            $params[] = '%' . trim($term) . '%';
        }

        // لو مفيش شروط، نرجع null
        if (empty($conditions)) {
            return null;
        }

        $sql .= implode(' OR ', $conditions);
        $sql .= ") ORDER BY 
                    CASE 
                        WHEN LOWER(name) LIKE LOWER(?) THEN 1
                        WHEN LOWER(name) LIKE LOWER(?) THEN 2
                        WHEN LOWER(name) LIKE LOWER(?) THEN 3
                        ELSE 4
                    END
                  LIMIT 1";

        // أضف شروط الترتيب حسب الأقرب للمطابقة
        $brandModel = trim($brand . ' ' . $model);
        $params[] = '%' . $brandModel . '%';
        $params[] = '%' . trim($brand) . '%';
        $params[] = '%' . $detectedCategory . '%';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $part = $stmt->fetch();

        if ($part) {
            return [
                'id' => $part['id'],
                'name' => $part['name'],
                'quantity' => $part['current_quantity'],
                'price' => $part['selling_price'],
                'keyword' => $detectedKeyword,
                'category' => $detectedCategory,
                'is_available' => $part['current_quantity'] > 0,
                'matched_term' => $brandModel
            ];
        }

        // لو مفيش قطعة متطابقة، نحاول نبحث بأقل شروط (الفئة فقط)
        $stmt = $this->db->prepare("
            SELECT id, name, current_quantity, selling_price 
            FROM inventory 
            WHERE current_quantity > 0 
              AND deleted_at IS NULL
              AND LOWER(category) LIKE LOWER(?)
            LIMIT 1
        ");
        $stmt->execute(['%' . $detectedCategory . '%']);
        $fallbackPart = $stmt->fetch();

        if ($fallbackPart) {
            return [
                'id' => $fallbackPart['id'],
                'name' => $fallbackPart['name'],
                'quantity' => $fallbackPart['current_quantity'],
                'price' => $fallbackPart['selling_price'],
                'keyword' => $detectedKeyword,
                'category' => $detectedCategory,
                'is_available' => $fallbackPart['current_quantity'] > 0,
                'matched_term' => $detectedCategory
            ];
        }

        // لو مفيش أي قطعة خالص
        return [
            'id' => null,
            'name' => null,
            'quantity' => 0,
            'price' => 0,
            'keyword' => $detectedKeyword,
            'category' => $detectedCategory,
            'is_available' => false,
            'matched_term' => null
        ];
    }
}