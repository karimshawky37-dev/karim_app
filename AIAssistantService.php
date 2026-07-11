<?php
namespace App\Services;

use App\Config\Database;
use PDO;

/**
 * خدمة المساعد الذكي المتطورة لتشخيص أعطال الأجهزة
 */
class AIAssistantService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * تشخيص العطل بناءً على نوع الجهاز ووصف المشكلة
     */
    public function diagnoseIssue(string $brand, string $model, string $issueDescription): array
    {
        $issue = mb_strtolower($issueDescription);
        $diagnoses = [];
        $steps = [];
        $technicianType = '';
        $confidence = 0.85;

        // ---- 1. أعطال الشاشة ----
        if ($this->containsAny($issue, ['شاشة', 'سكرين', 'توك', 'لمس', 'خطوط', 'ألوان', 'كسر', 'شرخ', 'إضاءة', 'ضباب'])) {
            if ($this->containsAny($issue, ['كسر', 'شرخ', 'مكسورة'])) {
                $diagnoses[] = 'كسر في الشاشة (احتمال 90%)';
                $diagnoses[] = 'تلف في طبقة التاتش (احتمال 5%)';
                $diagnoses[] = 'مشكلة في كابل الشاشة (احتمال 5%)';
                $steps[] = 'فك الجهاز وفحص الشاشة بصرياً';
                $steps[] = 'اختبار شاشة أخرى (إن وجدت)';
                $steps[] = 'تغيير الشاشة إذا كانت مكسورة';
            } elseif ($this->containsAny($issue, ['لمس', 'توك', 'ضغط'])) {
                $diagnoses[] = 'عطل في طبقة التاتش (احتمال 60%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 25%)';
                $diagnoses[] = 'كابل الشاشة مفكوك (احتمال 15%)';
                $steps[] = 'إعادة ضبط المصنع (ريست)';
                $steps[] = 'فحص كابل الشاشة';
                $steps[] = 'تغيير طبقة التاتش إذا لزم الأمر';
            } elseif ($this->containsAny($issue, ['خطوط', 'ألوان', 'إضاءة'])) {
                $diagnoses[] = 'عطل في IC الشاشة (احتمال 50%)';
                $diagnoses[] = 'مشكلة في كابل الشاشة (احتمال 30%)';
                $diagnoses[] = 'عطل في البوردة (احتمال 20%)';
                $steps[] = 'فحص كابل الشاشة وإعادة توصيله';
                $steps[] = 'قياس الفولتية على IC الشاشة';
                $steps[] = 'اختبار الشاشة بجهاز آخر';
            } else {
                $diagnoses[] = 'عطل عام في الشاشة (احتمال 50%)';
                $diagnoses[] = 'مشكلة في كابل الشاشة (احتمال 30%)';
                $diagnoses[] = 'عطل في البوردة (احتمال 20%)';
                $steps[] = 'الفحص البصري للشاشة';
                $steps[] = 'إعادة توصيل كابل الشاشة';
                $steps[] = 'اختبار الشاشة بجهاز آخر';
            }
            $technicianType = 'فني فك وتقفيل (أو فني بوردة إذا كان العطل في IC)';
        }

        // ---- 2. أعطال الصوت (سماعة - ميكرفون) ----
        elseif ($this->containsAny($issue, ['سماعة', 'صوت', 'ميكرفون', 'ميك', 'تكلم', 'تحدث', 'نغمة', 'رنين'])) {
            if ($this->containsAny($issue, ['سماعة', 'صوت', 'نغمة', 'رنين'])) {
                $diagnoses[] = 'عطل في سماعة الجهاز (احتمال 50%)';
                $diagnoses[] = 'مشكلة في مكبر الصوت (احتمال 25%)';
                $diagnoses[] = 'عطل في IC الصوت (احتمال 15%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 10%)';
                $steps[] = 'اختبار الصوت في وضع السماعة';
                $steps[] = 'فحص السماعة بصرياً';
                $steps[] = 'تنظيف منفذ السماعة';
                $steps[] = 'تغيير السماعة إذا لزم الأمر';
            } elseif ($this->containsAny($issue, ['ميكرفون', 'ميك', 'تكلم', 'تحدث'])) {
                $diagnoses[] = 'عطل في الميكرفون (احتمال 50%)';
                $diagnoses[] = 'مشكلة في IC الصوت (احتمال 25%)';
                $diagnoses[] = 'ثقب الميكرفون مسدود (احتمال 15%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 10%)';
                $steps[] = 'تنظيف ثقب الميكرفون';
                $steps[] = 'اختبار الميكرفون في تطبيق التسجيل';
                $steps[] = 'فحص الميكرفون بصرياً';
                $steps[] = 'تغيير الميكرفون إذا لزم الأمر';
            } else {
                $diagnoses[] = 'عطل في نظام الصوت (احتمال 40%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 30%)';
                $diagnoses[] = 'عطل في IC الصوت (احتمال 30%)';
                $steps[] = 'إعادة ضبط المصنع (ريست)';
                $steps[] = 'فحص مكونات الصوت';
                $steps[] = 'اختبار بسماعة خارجية';
            }
            $technicianType = 'فني بوردة ومعالجات (أو فني فك وتقفيل للاستبدال)';
        }

        // ---- 3. أعطال البطارية والشحن ----
        elseif ($this->containsAny($issue, ['بطارية', 'شحن', 'سخن', 'حرارة', 'فرغ', 'نفذ', 'بسرعة', 'انتفاخ'])) {
            if ($this->containsAny($issue, ['انتفاخ', 'تورم'])) {
                $diagnoses[] = 'بطارية منتفخة (خطورة!): يجب تغييرها فوراً (احتمال 95%)';
                $diagnoses[] = 'مشكلة في دائرة الشحن (احتمال 5%)';
                $steps[] = '⚠️ تحذير: لا تستخدم الجهاز!';
                $steps[] = 'فك الجهاز بحذر شديد';
                $steps[] = 'إزالة البطارية المنفوخة';
                $steps[] = 'تركيب بطارية جديدة أصلية';
            } elseif ($this->containsAny($issue, ['سخن', 'حرارة', 'ساخن'])) {
                $diagnoses[] = 'مشكلة في IC الشحن (احتمال 50%)';
                $diagnoses[] = 'شورت في البوردة (احتمال 25%)';
                $diagnoses[] = 'بطارية تالفة (احتمال 15%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 10%)';
                $steps[] = 'قياس الجهد على IC الشحن';
                $steps[] = 'فحص المكثفات حول منطقة الشحن';
                $steps[] = 'اختبار البطارية بجهاز خارجي';
            } elseif ($this->containsAny($issue, ['فرغ', 'بسرعة', 'نفذ'])) {
                $diagnoses[] = 'بطارية تالفة (احتمال 60%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 20%)';
                $diagnoses[] = 'تطبيقات تستهلك البطارية (احتمال 15%)';
                $diagnoses[] = 'عطل في البوردة (احتمال 5%)';
                $steps[] = 'فحص استهلاك البطارية من الإعدادات';
                $steps[] = 'إغلاق التطبيقات غير المستخدمة';
                $steps[] = 'اختبار البطارية بجهاز خارجي';
                $steps[] = 'تغيير البطارية إذا لزم الأمر';
            } elseif ($this->containsAny($issue, ['شحن', 'شاحن', 'كابل', 'مايشتغلش'])) {
                $diagnoses[] = 'عطل في منفذ الشحن (احتمال 40%)';
                $diagnoses[] = 'عطل في كابل الشحن (احتمال 20%)';
                $diagnoses[] = 'مشكلة في IC الشحن (احتمال 20%)';
                $diagnoses[] = 'بطارية تالفة (احتمال 15%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 5%)';
                $steps[] = 'تنظيف منفذ الشحن';
                $steps[] = 'اختبار بشاحن وكابل آخرين';
                $steps[] = 'قياس الجهد على منفذ الشحن';
                $steps[] = 'فحص IC الشحن';
            } else {
                $diagnoses[] = 'عطل في نظام الشحن (احتمال 40%)';
                $diagnoses[] = 'بطارية تالفة (احتمال 35%)';
                $diagnoses[] = 'مشكلة في البوردة (احتمال 25%)';
                $steps[] = 'اختبار بشاحن آخر';
                $steps[] = 'فحص منفذ الشحن';
                $steps[] = 'اختبار البطارية';
            }
            $technicianType = 'فني بوردة ومعالجات';
        }

        // ---- 4. أعطال الكاميرا ----
        elseif ($this->containsAny($issue, ['كاميرا', 'صورة', 'تصوير', 'فلاش', 'عدسة', 'ضبابية', 'مشوشة'])) {
            if ($this->containsAny($issue, ['فلاش', 'ضوء'])) {
                $diagnoses[] = 'عطل في فلاش الكاميرا (احتمال 50%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 25%)';
                $diagnoses[] = 'عطل في IC الكاميرا (احتمال 25%)';
                $steps[] = 'اختبار الفلاش في تطبيق الكاميرا';
                $steps[] = 'فحص إعدادات الفلاش';
                $steps[] = 'تغيير الفلاش إذا لزم الأمر';
            } elseif ($this->containsAny($issue, ['ضبابية', 'مشوشة', 'غير واضحة'])) {
                $diagnoses[] = 'عدسة الكاميرا متسخة (احتمال 40%)';
                $diagnoses[] = 'عطل في مستشعر الكاميرا (احتمال 30%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 20%)';
                $diagnoses[] = 'عطل في عدسة الكاميرا (احتمال 10%)';
                $steps[] = 'تنظيف عدسة الكاميرا';
                $steps[] = 'اختبار الكاميرا في تطبيق آخر';
                $steps[] = 'فحص مستشعر الكاميرا';
            } else {
                $diagnoses[] = 'عطل في الكاميرا (احتمال 40%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 30%)';
                $diagnoses[] = 'عطل في كابل الكاميرا (احتمال 20%)';
                $diagnoses[] = 'عطل في IC الكاميرا (احتمال 10%)';
                $steps[] = 'إعادة ضبط المصنع (ريست)';
                $steps[] = 'فحص كابل الكاميرا';
                $steps[] = 'اختبار كاميرا أخرى (إن وجدت)';
            }
            $technicianType = 'فني بوردة ومعالجات (أو فني فك وتقفيل للاستبدال)';
        }

        // ---- 5. أعطال الشبكة والاتصالات ----
        elseif ($this->containsAny($issue, ['شبكة', 'نت', 'واي فاي', 'بلوتوث', 'sim', 'شريحة', 'إشارة', 'موبايل', 'بيانات'])) {
            if ($this->containsAny($issue, ['واي فاي', 'wifi', 'وايرلس'])) {
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 40%)';
                $diagnoses[] = 'عطل في IC الواي فاي (احتمال 30%)';
                $diagnoses[] = 'مشكلة في Antenna الواي فاي (احتمال 20%)';
                $diagnoses[] = 'مشكلة في الراوتر (احتمال 10%)';
                $steps[] = 'إعادة تشغيل الجهاز';
                $steps[] = 'نسيان الشبكة وإعادة الاتصال';
                $steps[] = 'إعادة ضبط إعدادات الشبكة';
                $steps[] = 'فحص IC الواي فاي';
            } elseif ($this->containsAny($issue, ['بلوتوث', 'bluetooth'])) {
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 40%)';
                $diagnoses[] = 'عطل في IC البلوتوث (احتمال 30%)';
                $diagnoses[] = 'مشكلة في Antenna البلوتوث (احتمال 20%)';
                $diagnoses[] = 'تعارض مع جهاز آخر (احتمال 10%)';
                $steps[] = 'إعادة تشغيل الجهاز';
                $steps[] = 'إعادة ضبط إعدادات البلوتوث';
                $steps[] = 'فحص IC البلوتوث';
            } elseif ($this->containsAny($issue, ['شبكة', 'موبايل', 'بيانات', 'إشارة', 'sim', 'شريحة'])) {
                $diagnoses[] = 'IC Network تالف (احتمال 40%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 20%)';
                $diagnoses[] = 'Antenna مقطوعة أو تالفة (احتمال 20%)';
                $diagnoses[] = 'شريحة SIM تالفة (احتمال 15%)';
                $diagnoses[] = 'مشكلة في البوردة (احتمال 5%)';
                $steps[] = 'إعادة تشغيل الجهاز';
                $steps[] = 'اختبار بشريحة أخرى';
                $steps[] = 'إعادة ضبط إعدادات الشبكة';
                $steps[] = 'فحص IC Network باستخدام الملتيميتر';
                $steps[] = 'فحص وصلة الـ Antenna';
            } else {
                $diagnoses[] = 'عطل في نظام الاتصالات (احتمال 35%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 30%)';
                $diagnoses[] = 'عطل في Antenna (احتمال 20%)';
                $diagnoses[] = 'مشكلة في البوردة (احتمال 15%)';
                $steps[] = 'إعادة تشغيل الجهاز';
                $steps[] = 'إعادة ضبط إعدادات الشبكة';
                $steps[] = 'فحص الـ Antenna';
            }
            $technicianType = 'فني بوردة ومعالجات (أو فني سوفت وير أولاً)';
        }

        // ---- 6. أعطال الأزرار ----
        elseif ($this->containsAny($issue, ['زر', 'باور', 'طاقة', 'صوت', 'volume', 'power', 'home', 'قفل'])) {
            if ($this->containsAny($issue, ['باور', 'طاقة', 'power', 'قفل', 'lock'])) {
                $diagnoses[] = 'عطل في زر الباور (احتمال 50%)';
                $diagnoses[] = 'كابل الزر مفكوك (احتمال 25%)';
                $diagnoses[] = 'مشكلة في البوردة (احتمال 15%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 10%)';
                $steps[] = 'فحص زر الباور بصرياً';
                $steps[] = 'تنظيف الزر';
                $steps[] = 'فحص كابل الزر';
                $steps[] = 'تغيير زر الباور إذا لزم الأمر';
            } elseif ($this->containsAny($issue, ['صوت', 'volume'])) {
                $diagnoses[] = 'عطل في أزرار الصوت (احتمال 50%)';
                $diagnoses[] = 'كابل الأزرار مفكوك (احتمال 25%)';
                $diagnoses[] = 'مشكلة في البوردة (احتمال 15%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 10%)';
                $steps[] = 'فحص أزرار الصوت بصرياً';
                $steps[] = 'تنظيف الأزرار';
                $steps[] = 'فحص كابل الأزرار';
                $steps[] = 'تغيير الأزرار إذا لزم الأمر';
            } else {
                $diagnoses[] = 'عطل في الأزرار (احتمال 40%)';
                $diagnoses[] = 'مشكلة في الكابل (احتمال 30%)';
                $diagnoses[] = 'مشكلة في البوردة (احتمال 20%)';
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 10%)';
                $steps[] = 'فحص الأزرار بصرياً';
                $steps[] = 'تنظيف الأزرار';
                $steps[] = 'فحص الكابل';
            }
            $technicianType = 'فني فك وتقفيل';
        }

        // ---- 7. أعطال سوفت وير ----
        elseif ($this->containsAny($issue, ['سوفت', 'برمجي', 'تحديث', 'فلاش', 'ريستارت', 'تجمد', 'بطء', 'علق', 'إعادة تشغيل', 'بوت', 'لوجو', 'شعار'])) {
            if ($this->containsAny($issue, ['ريستارت', 'إعادة تشغيل', 'بوت', 'لوجو', 'شعار'])) {
                $diagnoses[] = 'مشكلة في نظام التشغيل (احتمال 50%)';
                $diagnoses[] = 'تحديث فاشل (احتمال 20%)';
                $diagnoses[] = 'فيروس أو برمجية خبيثة (احتمال 15%)';
                $diagnoses[] = 'مشكلة في الذاكرة الداخلية (احتمال 10%)';
                $diagnoses[] = 'عطل في البوردة (احتمال 5%)';
                $steps[] = 'عمل نسخة احتياطية للبيانات';
                $steps[] = 'إعادة تحميل السوفت وير (فلاشة)';
                $steps[] = 'مسح ذاكرة التخزين المؤقت (Cache)';
                $steps[] = 'تحديث النظام إلى أحدث إصدار';
            } elseif ($this->containsAny($issue, ['تجمد', 'علق', 'بطء', 'تقيل'])) {
                $diagnoses[] = 'ذاكرة التخزين ممتلئة (احتمال 40%)';
                $diagnoses[] = 'تطبيقات تستهلك الموارد (احتمال 25%)';
                $diagnoses[] = 'مشكلة في نظام التشغيل (احتمال 20%)';
                $diagnoses[] = 'فيروس (احتمال 10%)';
                $diagnoses[] = 'عطل في الذاكرة (احتمال 5%)';
                $steps[] = 'حذف الملفات والتطبيقات غير المستخدمة';
                $steps[] = 'إعادة تشغيل الجهاز';
                $steps[] = 'مسح ذاكرة التخزين المؤقت';
                $steps[] = 'إعادة ضبط المصنع (ريست)';
            } else {
                $diagnoses[] = 'مشكلة في سوفت وير (احتمال 50%)';
                $diagnoses[] = 'مشكلة في التحديثات (احتمال 25%)';
                $diagnoses[] = 'فيروس (احتمال 15%)';
                $diagnoses[] = 'مشكلة في الذاكرة (احتمال 10%)';
                $steps[] = 'إعادة تشغيل الجهاز';
                $steps[] = 'مسح ذاكرة التخزين المؤقت';
                $steps[] = 'إعادة ضبط المصنع (ريست)';
                $steps[] = 'تحديث النظام';
            }
            $technicianType = 'فني سوفت وير';
        }

        // ---- 8. أعطال الشحن (منفذ الشحن) ----
        elseif ($this->containsAny($issue, ['منفذ', 'شاحن', 'كابل', 'usb', 'type-c', 'micro', 'مايشتغلش', 'مايركبش'])) {
            $diagnoses[] = 'عطل في منفذ الشحن (احتمال 50%)';
            $diagnoses[] = 'كابل الشحن تالف (احتمال 20%)';
            $diagnoses[] = 'الشاحن تالف (احتمال 15%)';
            $diagnoses[] = 'مشكلة في IC الشحن (احتمال 10%)';
            $diagnoses[] = 'اتساخ منفذ الشحن (احتمال 5%)';
            $steps[] = 'تنظيف منفذ الشحن';
            $steps[] = 'اختبار بشاحن وكابل آخرين';
            $steps[] = 'فحص منفذ الشحن بصرياً';
            $steps[] = 'تغيير منفذ الشحن إذا لزم الأمر';
            $technicianType = 'فني فك وتقفيل (أو فني بوردة إذا كان العطل في IC)';
        }

        // ---- 9. أي عطل آخر غير محدد ----
        else {
            $diagnoses[] = 'عطل غير محدد في البوردة (احتمال 35%)';
            $diagnoses[] = 'مشكلة في سوفت وير (احlim 30%)';
            $diagnoses[] = 'عطل في أحد المكونات (احتمال 25%)';
            $diagnoses[] = 'سبب غير معروف (احتمال 10%)';
            
            $steps[] = 'الفحص البصري الكامل للجهاز';
            $steps[] = 'اختبار البطارية والشحن';
            $steps[] = 'إعادة ضبط المصنع (ريست)';
            $steps[] = 'إذا استمر العطل، فتح الجهاز وفحص البوردة';
            
            $technicianType = 'يفضل البدء بفني سوفت وير ثم التوجه لبوردة إذا لزم الأمر';
            $confidence = 0.70;
        }

        // تسجيل في قاعدة البيانات
        $this->logQuery($brand, $model, $issueDescription, $diagnoses, $steps, $technicianType);

        return [
            'brand' => $brand,
            'model' => $model,
            'issue' => $issueDescription,
            'diagnoses' => $diagnoses,
            'steps' => $steps,
            'technician_type' => $technicianType,
            'confidence' => $confidence
        ];
    }

    /**
     * التحقق من وجود أي كلمة من القائمة في النص
     */
    private function containsAny(string $text, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * تسجيل الاستعلام في قاعدة البيانات
     */
    private function logQuery(string $brand, string $model, string $issue, array $diagnoses, array $steps, string $technicianType): void
    {
        try {
            $stmt = $this->db->prepare("
               INSERT INTO ai_assistant_logs 
(user_id, device_id, query, response, confidence_score, suggested_actions, created_at) 
VALUES (1, NULL, ?, ?, 0.85, ?, NOW())
            ");
            
            $response = "التشخيصات: " . implode(' | ', $diagnoses) . "\nالخطوات: " . implode(' | ', $steps);
            $suggestedActions = json_encode([
                'diagnoses' => $diagnoses,
                'steps' => $steps,
                'technician_type' => $technicianType
            ]);
            
            $stmt->execute([
                "جهاز: $brand $model - العطل: $issue",
                $response,
                $suggestedActions
            ]);
        } catch (\Exception $e) {
            error_log('Failed to log AI query: ' . $e->getMessage());
        }
    }

    /**
     * استرجاع آخر الاستعلامات (تم إصلاح خطأ LIMIT)
     */
    public function getRecentQueries(int $limit = 5): array
    {
        $limit = (int) $limit;
        $stmt = $this->db->prepare("
            SELECT * FROM ai_assistant_logs 
            ORDER BY created_at DESC 
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}