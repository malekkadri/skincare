<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageHero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageHeroController extends Controller
{
    public function index(): View { return view('admin.page-heroes.index', ['heroes' => PageHero::ordered()->get()]); }
    public function create(): View { return view('admin.page-heroes.create', ['hero' => new PageHero()]); }
    public function edit(PageHero $pageHero): View { return view('admin.page-heroes.edit', ['hero' => $pageHero]); }
    public function store(Request $request): RedirectResponse { $data = $this->validateData($request); $this->handleImages($request, $data); PageHero::create($data); return to_route('admin.page-heroes.index')->with('success','Page hero created.'); }
    public function update(Request $request, PageHero $pageHero): RedirectResponse { $data=$this->validateData($request); $this->handleImages($request,$data,$pageHero); $pageHero->update($data); return back()->with('success','Page hero updated.'); }
    public function destroy(PageHero $pageHero): RedirectResponse { if($pageHero->image_path) Storage::disk('public')->delete($pageHero->image_path); if($pageHero->mobile_image_path) Storage::disk('public')->delete($pageHero->mobile_image_path); $pageHero->delete(); return to_route('admin.page-heroes.index')->with('success','Page hero deleted.'); }
    private function validateData(Request $request): array { $data = $request->validate(['page_key'=>['required','string','max:100'],'title_fr'=>['nullable','string','max:255'],'title_en'=>['nullable','string','max:255'],'subtitle_fr'=>['nullable','string','max:255'],'subtitle_en'=>['nullable','string','max:255'],'description_fr'=>['nullable','string'],'description_en'=>['nullable','string'],'image'=>['nullable','mimes:jpg,jpeg,png,webp,heic,heif','max:6144'],'mobile_image'=>['nullable','mimes:jpg,jpeg,png,webp,heic,heif','max:6144'],'alt_text_fr'=>['nullable','string','max:255'],'alt_text_en'=>['nullable','string','max:255'],'cta_label_fr'=>['nullable','string','max:255'],'cta_label_en'=>['nullable','string','max:255'],'cta_url'=>['nullable','url','max:255'],'sort_order'=>['nullable','integer'],'overlay_opacity'=>['nullable','numeric','min:0','max:0.9'],'is_active'=>['nullable','boolean']]); $data['is_active']=$request->boolean('is_active'); unset($data['image'],$data['mobile_image']); return $data; }
    private function handleImages(Request $request, array &$data, ?PageHero $hero = null): void { if($request->hasFile('image')){ if($hero?->image_path) Storage::disk('public')->delete($hero->image_path); $data['image_path']=$request->file('image')->store('page-heroes','public'); } if($request->hasFile('mobile_image')){ if($hero?->mobile_image_path) Storage::disk('public')->delete($hero->mobile_image_path); $data['mobile_image_path']=$request->file('mobile_image')->store('page-heroes','public'); } }
}
