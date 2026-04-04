<?php

use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AiContentHelperController;
use App\Http\Controllers\Admin\AiSettingsController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AvailabilityController as AdminAvailabilityController;
use App\Http\Controllers\Admin\BlockedDateController;
use App\Http\Controllers\Admin\BlockedTimeRangeController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\Ops\BackupController;
use App\Http\Controllers\Admin\Ops\HealthController;
use App\Http\Controllers\Admin\Ops\LaunchReadinessController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\Reports\AppointmentReportController;
use App\Http\Controllers\Admin\Reports\ConsultationReportController;
use App\Http\Controllers\Admin\Reports\OverviewReportController;
use App\Http\Controllers\Admin\Reports\ReportExportController;
use App\Http\Controllers\Admin\Reports\RevenueReportController;
use App\Http\Controllers\Admin\Reports\ServicePerformanceReportController;
use App\Http\Controllers\Admin\Reports\WhatsAppReportController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\WhatsAppLogController;
use App\Http\Controllers\Admin\WhatsAppSettingsController;
use App\Http\Controllers\Admin\WhatsAppTemplateController;
use App\Http\Controllers\Booking\AvailabilityController;
use App\Http\Controllers\Booking\BookingWizardController;
use App\Http\Controllers\Public\ConsultationController;
use App\Http\Controllers\Public\ServiceRecommenderController;
use App\Http\Controllers\PublicPreferenceController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::middleware('public.preferences')->group(function () {
    Route::get('/', [PublicSiteController::class, 'home'])->name('home');
    Route::get('/about', [PublicSiteController::class, 'about'])->name('about');
    Route::get('/services', [PublicSiteController::class, 'services'])->name('services.index');
    Route::get('/services/{slug}', [PublicSiteController::class, 'service'])->name('services.show');
    Route::get('/gallery', [PublicSiteController::class, 'gallery'])->name('gallery');
    Route::get('/testimonials', [PublicSiteController::class, 'testimonials'])->name('testimonials');
    Route::get('/faq', [PublicSiteController::class, 'faq'])->name('faq');
    Route::get('/contact', [PublicSiteController::class, 'contact'])->name('contact');
    Route::get('/policies/{policy}', [PublicSiteController::class, 'policy'])->name('policies.show');

    Route::post('/preferences/locale', [PublicPreferenceController::class, 'locale'])->name('preferences.locale');
    Route::post('/preferences/currency', [PublicPreferenceController::class, 'currency'])->name('preferences.currency');

    Route::get('/consultation', [ConsultationController::class, 'create'])->name('consultation.create');
    Route::post('/consultation', [ConsultationController::class, 'store'])->name('consultation.store');
    Route::get('/consultation/success/{consultation}', [ConsultationController::class, 'success'])->name('consultation.success');

    Route::get('/service-recommender', [ServiceRecommenderController::class, 'index'])->name('recommender.index');
    Route::post('/service-recommender', [ServiceRecommenderController::class, 'recommend'])->name('recommender.recommend');

    Route::get('/booking/available-slots', AvailabilityController::class)->name('booking.available-slots');

    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingWizardController::class, 'service'])->name('service');
        Route::post('/service', [BookingWizardController::class, 'saveService'])->name('service.save');
        Route::get('/date', [BookingWizardController::class, 'date'])->name('date');
        Route::post('/date', [BookingWizardController::class, 'saveDate'])->name('date.save');
        Route::get('/slot', [BookingWizardController::class, 'slot'])->name('slot');
        Route::post('/slot', [BookingWizardController::class, 'saveSlot'])->name('slot.save');
        Route::get('/customer', [BookingWizardController::class, 'customer'])->name('customer');
        Route::post('/customer', [BookingWizardController::class, 'saveCustomer'])->name('customer.save');
        Route::get('/review', [BookingWizardController::class, 'review'])->name('review');
        Route::post('/confirm', [BookingWizardController::class, 'confirm'])->name('confirm');
        Route::get('/success/{token}', [BookingWizardController::class, 'success'])->name('success');
    });
});

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: ".route('sitemap')."\n", 200, ['Content-Type' => 'text/plain']);
});
Route::get('/health', [HealthController::class, 'probe'])->name('health.probe');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::middleware('permission:manage_settings')->group(function () {
            Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        });

        Route::middleware('permission:manage_ai')->group(function () {
            Route::get('/ai-settings', [AiSettingsController::class, 'edit'])->name('ai-settings.edit');
            Route::put('/ai-settings', [AiSettingsController::class, 'update'])->name('ai-settings.update');
            Route::get('/ai-content-helper', [AiContentHelperController::class, 'index'])->name('ai-content-helper.index');
            Route::post('/ai-content-helper', [AiContentHelperController::class, 'generate'])->name('ai-content-helper.generate');
        });

        Route::middleware('permission:manage_cms')->group(function () {
            Route::get('/homepage', [HomepageSectionController::class, 'index'])->name('homepage.index');
            Route::get('/homepage/{homepage}/edit', [HomepageSectionController::class, 'edit'])->name('homepage.edit');
            Route::put('/homepage/{homepage}', [HomepageSectionController::class, 'update'])->name('homepage.update');
            Route::get('/about', [AboutPageController::class, 'edit'])->name('about.edit');
            Route::put('/about', [AboutPageController::class, 'update'])->name('about.update');
            Route::resource('gallery', GalleryItemController::class)->except('show')->parameter('gallery', 'gallery');
            Route::resource('testimonials', TestimonialController::class)->except('show');
            Route::resource('faq', FaqController::class)->except('show');
            Route::resource('policies', PolicyController::class)->except('show');
        });

        Route::middleware('permission:manage_whatsapp')->group(function () {
            Route::get('/whatsapp/settings', [WhatsAppSettingsController::class, 'edit'])->name('whatsapp.settings.edit');
            Route::put('/whatsapp/settings', [WhatsAppSettingsController::class, 'update'])->name('whatsapp.settings.update');
            Route::get('/whatsapp/templates', [WhatsAppTemplateController::class, 'index'])->name('whatsapp.templates.index');
            Route::put('/whatsapp/templates/{template}', [WhatsAppTemplateController::class, 'update'])->name('whatsapp.templates.update');
            Route::get('/whatsapp/logs', [WhatsAppLogController::class, 'index'])->name('whatsapp.logs.index');
            Route::get('/whatsapp/logs/{log}', [WhatsAppLogController::class, 'show'])->name('whatsapp.logs.show');
            Route::post('/whatsapp/logs/{log}/retry', [WhatsAppLogController::class, 'retry'])->name('whatsapp.logs.retry');
        });

        Route::middleware('permission:manage_services')->group(function () {
            Route::resource('categories', ServiceCategoryController::class)->except('show');
            Route::resource('services', ServiceController::class)->except('show');
        });

        Route::middleware('permission:manage_consultations')->group(function () {
            Route::resource('consultations', AdminConsultationController::class)->only(['index', 'show', 'update']);
            Route::post('consultations/{consultation}/retry-analysis', [AdminConsultationController::class, 'retryAnalysis'])->name('consultations.retry-analysis');
            Route::get('consultation-images/{image}', [AdminConsultationController::class, 'image'])->name('consultations.image');
        });

        Route::middleware('permission:manage_appointments')->group(function () {
            Route::resource('appointments', AppointmentController::class)->except('destroy');
            Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
            Route::post('appointments/{appointment}/resend-confirmation', [AppointmentController::class, 'resendConfirmation'])->name('appointments.resend-confirmation');
            Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
        });

        Route::middleware('permission:manage_availability')->group(function () {
            Route::get('availability', [AdminAvailabilityController::class, 'edit'])->name('availability.edit');
            Route::put('availability/hours', [AdminAvailabilityController::class, 'updateHours'])->name('availability.hours.update');
            Route::put('availability/settings', [AdminAvailabilityController::class, 'updateSettings'])->name('availability.settings.update');
            Route::get('blocked-dates', [BlockedDateController::class, 'index'])->name('blocked-dates.index');
            Route::post('blocked-dates', [BlockedDateController::class, 'store'])->name('blocked-dates.store');
            Route::delete('blocked-dates/{blockedDate}', [BlockedDateController::class, 'destroy'])->name('blocked-dates.destroy');
            Route::get('blocked-times', [BlockedTimeRangeController::class, 'index'])->name('blocked-times.index');
            Route::post('blocked-times', [BlockedTimeRangeController::class, 'store'])->name('blocked-times.store');
            Route::delete('blocked-times/{blockedTime}', [BlockedTimeRangeController::class, 'destroy'])->name('blocked-times.destroy');
        });

        Route::middleware('permission:view_reports')->prefix('reports')->name('reports.')->group(function () {
            Route::get('/', OverviewReportController::class)->name('overview');
            Route::get('/appointments', AppointmentReportController::class)->name('appointments');
            Route::get('/revenue', RevenueReportController::class)->name('revenue');
            Route::get('/services', ServicePerformanceReportController::class)->name('services');
            Route::get('/consultations', ConsultationReportController::class)->name('consultations');
            Route::get('/whatsapp', WhatsAppReportController::class)->name('whatsapp');

            Route::middleware('permission:export_reports')->group(function () {
                Route::get('/exports/appointments', [ReportExportController::class, 'appointments'])->name('exports.appointments');
                Route::get('/exports/consultations', [ReportExportController::class, 'consultations'])->name('exports.consultations');
                Route::get('/exports/whatsapp', [ReportExportController::class, 'whatsapp'])->name('exports.whatsapp');
            });
        });

        Route::middleware('permission:manage_admin_users')->resource('users', AdminUserController::class)->only(['index', 'create', 'store', 'edit', 'update']);

        Route::middleware('permission:view_system_health')->get('/ops/health', [HealthController::class, 'index'])->name('ops.health');
        Route::middleware('permission:view_system_health')->get('/ops/launch-readiness', [LaunchReadinessController::class, 'index'])->name('ops.launch-readiness');
        Route::middleware('permission:manage_backups')->post('/ops/backups', [BackupController::class, 'store'])->name('ops.backups.store');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

Route::redirect('/book', '/booking');
Route::redirect('/login', '/admin/login')->name('login');
