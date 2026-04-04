<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('admin')->after('is_admin');
            $table->boolean('is_active')->default(true)->after('role');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->string('meta_title_fr')->nullable()->after('description_en');
            $table->string('meta_title_en')->nullable()->after('meta_title_fr');
            $table->text('meta_description_fr')->nullable()->after('meta_title_en');
            $table->text('meta_description_en')->nullable()->after('meta_description_fr');
        });

        Schema::table('settings', function (Blueprint $table): void {
            $table->string('seo_home_title_fr')->nullable()->after('opening_hours_en');
            $table->string('seo_home_title_en')->nullable()->after('seo_home_title_fr');
            $table->text('seo_home_description_fr')->nullable()->after('seo_home_title_en');
            $table->text('seo_home_description_en')->nullable()->after('seo_home_description_fr');
            $table->string('seo_services_title_fr')->nullable()->after('seo_home_description_en');
            $table->string('seo_services_title_en')->nullable()->after('seo_services_title_fr');
            $table->text('seo_services_description_fr')->nullable()->after('seo_services_title_en');
            $table->text('seo_services_description_en')->nullable()->after('seo_services_description_fr');
            $table->string('seo_gallery_title_fr')->nullable()->after('seo_services_description_en');
            $table->string('seo_gallery_title_en')->nullable()->after('seo_gallery_title_fr');
            $table->text('seo_gallery_description_fr')->nullable()->after('seo_gallery_title_en');
            $table->text('seo_gallery_description_en')->nullable()->after('seo_gallery_description_fr');
            $table->string('seo_testimonials_title_fr')->nullable()->after('seo_gallery_description_en');
            $table->string('seo_testimonials_title_en')->nullable()->after('seo_testimonials_title_fr');
            $table->text('seo_testimonials_description_fr')->nullable()->after('seo_testimonials_title_en');
            $table->text('seo_testimonials_description_en')->nullable()->after('seo_testimonials_description_fr');
            $table->string('seo_faq_title_fr')->nullable()->after('seo_testimonials_description_en');
            $table->string('seo_faq_title_en')->nullable()->after('seo_faq_title_fr');
            $table->text('seo_faq_description_fr')->nullable()->after('seo_faq_title_en');
            $table->text('seo_faq_description_en')->nullable()->after('seo_faq_description_fr');
            $table->string('seo_contact_title_fr')->nullable()->after('seo_faq_description_en');
            $table->string('seo_contact_title_en')->nullable()->after('seo_contact_title_fr');
            $table->text('seo_contact_description_fr')->nullable()->after('seo_contact_title_en');
            $table->text('seo_contact_description_en')->nullable()->after('seo_contact_description_fr');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'seo_home_title_fr', 'seo_home_title_en', 'seo_home_description_fr', 'seo_home_description_en',
                'seo_services_title_fr', 'seo_services_title_en', 'seo_services_description_fr', 'seo_services_description_en',
                'seo_gallery_title_fr', 'seo_gallery_title_en', 'seo_gallery_description_fr', 'seo_gallery_description_en',
                'seo_testimonials_title_fr', 'seo_testimonials_title_en', 'seo_testimonials_description_fr', 'seo_testimonials_description_en',
                'seo_faq_title_fr', 'seo_faq_title_en', 'seo_faq_description_fr', 'seo_faq_description_en',
                'seo_contact_title_fr', 'seo_contact_title_en', 'seo_contact_description_fr', 'seo_contact_description_en',
            ]);
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['meta_title_fr', 'meta_title_en', 'meta_description_fr', 'meta_description_en']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
