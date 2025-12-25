# إعداد البريد الإلكتروني - Dasa's Cake

## معلومات الخادم

**البريد الإلكتروني:** info@dasa.ly
**الخادم الوارد (Incoming):** ls53.server.ly
**الخادم الصادر (Outgoing):** ls53.server.ly

### المنافذ (Ports)
- **IMAP Port:** 993
- **POP3 Port:** 995
- **SMTP Port:** 465 (SSL/TLS)

## خطوات الإعداد

### 1. تحديث ملف `.env`

قم بنسخ المحتوى التالي إلى ملف `.env` الخاص بك:

```env
MAIL_MAILER=smtp
MAIL_HOST=ls53.server.ly
MAIL_PORT=465
MAIL_USERNAME=info@dasa.ly
MAIL_PASSWORD="]S8%EnD4vh@7R!eM"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@dasa.ly"
MAIL_FROM_NAME="Dasa's Cake"
ADMIN_EMAIL="info@dasa.ly"
```

### 2. مسح الكاش (Cache)

بعد تحديث ملف `.env`، قم بتشغيل الأوامر التالية:

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. اختبار إرسال البريد

يمكنك اختبار إعدادات البريد باستخدام Tinker:

```bash
php artisan tinker
```

ثم قم بتشغيل:

```php
Mail::raw('اختبار إرسال بريد من Dasa\'s Cake', function($message) {
    $message->to('your-test-email@example.com')
            ->subject('اختبار البريد الإلكتروني');
});
```

### 4. التحقق من إعدادات `config/mail.php`

تأكد من أن ملف `config/mail.php` يحتوي على الإعدادات الصحيحة. Laravel 11 يجب أن يكون لديه إعدادات افتراضية مناسبة.

## إعدادات إضافية (اختياري)

### للاستخدام في بيئة الإنتاج

في بيئة الإنتاج، تأكد من:

1. **تشفير البريد الإلكتروني:** استخدام SSL (Port 465)
2. **التحقق من الهوية:** SMTP يتطلب المصادقة
3. **حد الإرسال:** تحقق من حدود الخادم لعدد الرسائل في اليوم

### البريد في Laravel

النظام الحالي يستخدم البريد الإلكتروني في:

1. **تأكيد الطلبات:** عند إنشاء طلب جديد
2. **إشعار الإدارة:** عند استلام طلب جديد
3. **تحديثات الطلب:** عند تغيير حالة الطلب

## استكشاف الأخطاء

### مشكلة: فشل الاتصال بالخادم

**الحل:**
- تأكد من أن Port 465 غير محظور بواسطة Firewall
- تحقق من اسم المستخدم وكلمة المرور
- تأكد من أن `MAIL_ENCRYPTION=ssl` موجودة

### مشكلة: البريد لا يرسل

**الحل:**
```bash
# تحقق من سجلات Laravel
tail -f storage/logs/laravel.log

# مسح جميع الكاش
php artisan optimize:clear
```

### مشكلة: البريد يذهب إلى Spam

**الحل:**
- تأكد من إعداد SPF و DKIM records للدومين
- استخدم FROM address رسمي (info@dasa.ly)
- تجنب الكلمات التي تثير فلاتر Spam

## الأمان

⚠️ **مهم جداً:**

1. **لا تشارك كلمة المرور:** احتفظ بملف `.env` آمناً
2. **أضف `.env` إلى `.gitignore`:** لا ترفع كلمات المرور إلى Git
3. **استخدم متغيرات البيئة:** في الإنتاج، استخدم متغيرات بيئة الخادم

## جهات الاتصال

- **البريد الإلكتروني:** info@dasa.ly
- **الدعم الفني:** راجع مزود الاستضافة (ls53.server.ly)

---

تم التحديث: 2025-12-25
