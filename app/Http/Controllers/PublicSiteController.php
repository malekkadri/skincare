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
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'settings' => Setting::current(),
            'sections' => HomepageSection::active()->ordered()->get()->keyBy('key'),
            'featuredServices' => \App\Models\Service::active()->featured()->ordered()->take(6)->get(),
            'featuredGallery' => GalleryItem::active()->featured()->ordered()->take(6)->get(),
            'featuredTestimonials' => Testimonial::active()->featured()->ordered()->take(3)->get(),
        ]);
    }
    public function about(): View { return view('public.about', ['about' => AboutPage::published()->first(), 'settings' => Setting::current()]); }
    public function services(): View { return view('public.services', ['categories' => ServiceCategory::active()->ordered()->with(['services' => fn($q) => $q->active()->ordered()])->get(), 'settings' => Setting::current()]); }
    public function service(string $slug): View { $service=\App\Models\Service::active()->where('slug',$slug)->firstOrFail(); return view('public.service-show', compact('service')); }
    public function gallery(): View { return view('public.gallery', ['items' => GalleryItem::active()->ordered()->get()]); }
    public function testimonials(): View { return view('public.testimonials', ['items' => Testimonial::active()->ordered()->get()]); }
    public function faq(): View { return view('public.faq', ['items' => Faq::active()->ordered()->get()->groupBy(fn($faq)=>$faq->category ?: 'General')]); }
    public function contact(): View { return view('public.contact', ['settings' => Setting::current()]); }
    public function policy(Policy $policy): View { abort_if(! $policy->is_active, 404); return view('public.policy', ['policy' => $policy]); }
}
