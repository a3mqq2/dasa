@extends('layouts.website')

@section('title', 'معلومات - Dasa\'s Cake')

@push('styles')
<style>
    .about-header {
        text-align: center;
        padding: 2rem 0 3rem;
    }

    .about-header img {
        max-width: 180px;
        margin-bottom: 1.5rem;
    }

    .about-header h1 {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .about-header p {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .info-card {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .info-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 20px rgba(245, 71, 107, 0.15);
    }

    .info-card h3 {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.35rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-card h3 i {
        font-size: 1.75rem;
    }

    .info-card p {
        color: #495057;
        line-height: 1.9;
        margin-bottom: 1rem;
    }

    .contact-links {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .contact-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--light-bg);
        border-radius: 12px;
        text-decoration: none;
        color: var(--secondary-color);
        transition: all 0.3s;
    }

    .contact-link:hover {
        background: var(--primary-color);
        color: #fff;
        transform: translateX(-5px);
    }

    .contact-link i {
        font-size: 2rem;
    }

    .contact-link .contact-info {
        flex: 1;
    }

    .contact-link .contact-label {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-bottom: 0.25rem;
    }

    .contact-link .contact-value {
        font-weight: 600;
        font-size: 1.05rem;
    }

    .feature-list {
        list-style: none;
        padding: 0;
    }

    .feature-list li {
        padding: 0.75rem 0;
        border-bottom: 1px dashed var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #495057;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-list li i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .social-links {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .social-link {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--light-bg);
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.75rem;
        transition: all 0.3s;
        text-decoration: none;
    }

    .social-link:hover {
        background: var(--primary-color);
        color: #fff;
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .developer-info {
        text-align: center;
        margin-top: 3rem;
        padding: 1.5rem;
        border-top: 1px dashed var(--border-color);
    }

    .developer-info p {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .developer-info a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .developer-info a:hover {
        color: var(--secondary-color);
    }

    .developer-info i {
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')
<div class="about-header">
    <h1>Dasa's Cake</h1>
    <p>حلويات من القلب لأحبائك</p>
</div>

<!-- About Project -->
<div class="info-card">
    <h3>
        <i class="ph-duotone ph-sparkle"></i>
        معلومات
    </h3>
    <p>
        نحن في Dasa's Cake نقدم أجود أنواع الحلويات والكيك المصنوعة بعناية فائقة وحب.
        نؤمن بأن كل مناسبة تستحق حلوى مميزة، لذلك نحرص على تقديم منتجات عالية الجودة
        بنكهات رائعة وتصاميم جذابة.
    </p>
    <p>
        سواء كنت تبحث عن كيكة عيد ميلاد، حلوى لمناسبة خاصة، أو فقط تريد تدليل نفسك
        بشيء لذيذ، نحن هنا لنجعل يومك أحلى!
    </p>
</div>

<!-- Our Services -->
<div class="info-card">
    <h3>
        <i class="ph-duotone ph-check-circle"></i>
        خدماتنا
    </h3>
    <ul class="feature-list">
        <li>
            <i class="ph-duotone ph-lightning"></i>
            <span>تسليم فوري للمنتجات المتوفرة في المخزون</span>
        </li>
        <li>
            <i class="ph-duotone ph-calendar-check"></i>
            <span>حجز مسبق مع اختيار تاريخ التسليم المناسب</span>
        </li>
        <li>
            <i class="ph-duotone ph-truck"></i>
            <span>خدمة توصيل سريعة وموثوقة</span>
        </li>
        <li>
            <i class="ph-duotone ph-heart"></i>
            <span>منتجات طازجة ومصنوعة بحب</span>
        </li>
        <li>
            <i class="ph-duotone ph-palette"></i>
            <span>تصاميم مخصصة حسب طلبك</span>
        </li>
    </ul>
</div>

<!-- Contact Information -->
<div class="info-card">
    <h3>
        <i class="ph-duotone ph-phone-call"></i>
        تواصل معنا
    </h3>

    <div class="contact-links">
        <a href="tel:0910739550" class="contact-link">
            <i class="ph-duotone ph-phone"></i>
            <div class="contact-info">
                <div class="contact-label">اتصل بنا</div>
                <div class="contact-value">0910739550</div>
            </div>
        </a>

        <a href="https://wa.me/218912345678" target="_blank" class="contact-link">
            <i class="ph-duotone ph-whatsapp-logo"></i>
            <div class="contact-info">
                <div class="contact-label">واتساب</div>
                <div class="contact-value">مراسلتنا عبر واتساب</div>
            </div>
        </a>

        <a href="https://www.facebook.com/groups/3322839371273660" target="_blank" class="contact-link">
            <i class="ph-duotone ph-facebook-logo"></i>
            <div class="contact-info">
                <div class="contact-label">فيسبوك</div>
                <div class="contact-value">تابعنا على فيسبوك</div>
            </div>
        </a>
    </div>

    <div class="social-links">
        <a href="https://www.facebook.com/groups/3322839371273660" target="_blank" class="social-link" title="فيسبوك">
            <i class="ph-duotone ph-facebook-logo"></i>
        </a>
        <a href="https://wa.me/218912345678" target="_blank" class="social-link" title="واتساب">
            <i class="ph-duotone ph-whatsapp-logo"></i>
        </a>
        <a href="tel:0910739550" class="social-link" title="اتصل بنا">
            <i class="ph-duotone ph-phone"></i>
        </a>
    </div>
</div>

<!-- Order Now CTA -->
<div class="text-center mt-4 mb-4">
    <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">
        <i class="ph-duotone ph-shopping-cart me-2"></i>
        ابدأ الطلب الآن
    </a>
</div>

<!-- Developer Info -->
<div class="developer-info">
    <p>تم تطوير النظام بواسطة <strong>Aisha Altiri</strong></p>
    <a href="https://wa.me/218944336674" target="_blank">
        <i class="ph-duotone ph-whatsapp-logo"></i>
        218944336674
    </a>
</div>

@endsection
