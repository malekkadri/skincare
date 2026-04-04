<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryItemRequest;
use App\Http\Requests\Admin\UpdateGalleryItemRequest;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
class GalleryItemController extends Controller
{
    public function index(): View { return view('admin.gallery.index', ['items' => GalleryItem::ordered()->paginate(20)]); }
    public function create(): View { return view('admin.gallery.create', ['item' => new GalleryItem()]); }
    public function store(StoreGalleryItemRequest $request): RedirectResponse { $data=$request->validated();$data['is_before_after']=$request->boolean('is_before_after');$data['is_featured']=$request->boolean('is_featured');$data['is_active']=$request->boolean('is_active',true);$data['image_path']=$request->file('image')->store('gallery','public');unset($data['image']);GalleryItem::create($data);return to_route('admin.gallery.index')->with('success','Gallery item created.'); }
    public function edit(GalleryItem $gallery): View { return view('admin.gallery.edit', ['item' => $gallery]); }
    public function update(UpdateGalleryItemRequest $request, GalleryItem $gallery): RedirectResponse { $data=$request->validated();$data['is_before_after']=$request->boolean('is_before_after');$data['is_featured']=$request->boolean('is_featured');$data['is_active']=$request->boolean('is_active'); if($request->hasFile('image')){ if($gallery->image_path) Storage::disk('public')->delete($gallery->image_path); $data['image_path']=$request->file('image')->store('gallery','public'); } unset($data['image']); $gallery->update($data); return back()->with('success','Gallery item updated.'); }
    public function destroy(GalleryItem $gallery): RedirectResponse { if($gallery->image_path) Storage::disk('public')->delete($gallery->image_path); $gallery->delete(); return back()->with('success','Gallery item deleted.'); }
}
