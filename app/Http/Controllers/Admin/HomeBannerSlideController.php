<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBannerSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeBannerSlideController extends Controller
{
    public function index(): View { return view('admin.home-banners.index', ['slides' => HomeBannerSlide::ordered()->get()]); }
    public function create(): View { return view('admin.home-banners.create', ['slide' => new HomeBannerSlide()]); }
    public function edit(HomeBannerSlide $homeBanner): View { return view('admin.home-banners.edit', ['slide' => $homeBanner]); }
    public function store(Request $request): RedirectResponse { $data = $this->validateData($request); $this->handleImages($request, $data); HomeBannerSlide::create($data); return to_route('admin.home-banners.index')->with('success','Home banner slide created.'); }
    public function update(Request $request, HomeBannerSlide $homeBanner): RedirectResponse { $data = $this->validateData($request); $this->handleImages($request, $data, $homeBanner); $homeBanner->update($data); return back()->with('success','Home banner slide updated.'); }
    public function destroy(HomeBannerSlide $homeBanner): RedirectResponse { if($homeBanner->image_path) Storage::disk('public')->delete($homeBanner->image_path); if($homeBanner->mobile_image_path) Storage::disk('public')->delete($homeBanner->mobile_image_path); $homeBanner->delete(); return to_route('admin.home-banners.index')->with('success','Home banner slide deleted.'); }
    private function validateData(Request $request): array { $data=$request->validate(['title_fr'=>['nullable','string','max:255'],'title_en'=>['nullable','string','max:255'],'subtitle_fr'=>['nullable','string','max:255'],'subtitle_en'=>['nullable','string','max:255'],'description_fr'=>['nullable','string'],'description_en'=>['nullable','string'],'image'=>['nullable','mimes:jpg,jpeg,png,webp,heic,heif','max:6144'],'mobile_image'=>['nullable','mimes:jpg,jpeg,png,webp,heic,heif','max:6144'],'alt_text_fr'=>['nullable','string','max:255'],'alt_text_en'=>['nullable','string','max:255'],'cta_label_fr'=>['nullable','string','max:255'],'cta_label_en'=>['nullable','string','max:255'],'cta_url'=>['nullable','url','max:255'],'sort_order'=>['nullable','integer'],'is_active'=>['nullable','boolean']]); $data['is_active']=$request->boolean('is_active'); unset($data['image'],$data['mobile_image']); return $data; }
    private function handleImages(Request $request, array &$data, ?HomeBannerSlide $slide = null): void { if($request->hasFile('image')){ if($slide?->image_path) Storage::disk('public')->delete($slide->image_path); $data['image_path']=$request->file('image')->store('home-banners','public'); } if($request->hasFile('mobile_image')){ if($slide?->mobile_image_path) Storage::disk('public')->delete($slide->mobile_image_path); $data['mobile_image_path']=$request->file('mobile_image')->store('home-banners','public'); }}
}
