<?php
namespace App\Http\Controllers;

use App\Models\AboutPage;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\Policy;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Services\Seo\SeoService;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function __construct(protected SeoService $seoService)
    {
    }

    public function home(): View
    {
        $settings = Setting::current();

        return view('public.home', [
            'settings' => $settings,
            'sections' => HomepageSection::active()->ordered()->get()->keyBy('key'),
            'featuredServices' => \App\Models\Service::active()->featured()->ordered()->take(6)->get(),
            'featuredGallery' => GalleryItem::active()->featured()->ordered()->take(6)->get(),
            'featuredTestimonials' => Testimonial::active()->featured()->ordered()->take(3)->get(),
            'seo' => $this->seoService->forPage('home', route('home')),
        ]);
    }
    public function about(): View
    {
        $about = AboutPage::published()->first();

        return view('public.about', ['about' => $about, 'settings' => Setting::current(), 'seo' => $this->seoService->forAbout($about)]);
    }
    public function services(): View { return view('public.services', ['categories' => ServiceCategory::active()->ordered()->with(['services' => fn($q) => $q->active()->ordered()])->get(), 'settings' => Setting::current(), 'seo' => $this->seoService->forPage('services', route('services.index'))]); }
    public function service(string $slug): View { $service=\App\Models\Service::active()->where('slug',$slug)->firstOrFail(); return view('public.service-show', ['service' => $service, 'settings' => Setting::current(), 'seo' => $this->seoService->forService($service)]); }
    public function gallery(): View { return view('public.gallery', ['items' => GalleryItem::active()->ordered()->get(), 'settings' => Setting::current(), 'seo' => $this->seoService->forPage('gallery', route('gallery'))]); }
    public function testimonials(): View { return view('public.testimonials', ['items' => Testimonial::active()->ordered()->get(), 'settings' => Setting::current(), 'seo' => $this->seoService->forPage('testimonials', route('testimonials'))]); }
    public function faq(): View { return view('public.faq', ['items' => Faq::active()->ordered()->get()->groupBy(fn($faq)=>$faq->category ?: 'General'), 'settings' => Setting::current(), 'seo' => $this->seoService->forPage('faq', route('faq'))]); }
    public function contact(): View { return view('public.contact', ['settings' => Setting::current(), 'seo' => $this->seoService->forPage('contact', route('contact'))]); }
    public function policy(Policy $policy): View { abort_if(! $policy->is_active, 404); return view('public.policy', ['policy' => $policy, 'settings' => Setting::current(), 'seo' => new \App\Services\Seo\SeoData($policy->{'meta_title_'.app()->getLocale()} ?: $policy->localized_title, (string) ($policy->{'meta_description_'.app()->getLocale()} ?: str($policy->localized_content)->limit(155)), route('policies.show', $policy))]); }
}
