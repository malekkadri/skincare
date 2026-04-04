<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageRequest;
use App\Models\AboutPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    public function edit(): View { return view('admin.about.edit', ['about' => AboutPage::query()->firstOrCreate(['id' => 1], ['title_fr' => 'À propos', 'title_en' => 'About'])]); }
    public function update(UpdateAboutPageRequest $request): RedirectResponse
    {
        $about = AboutPage::query()->firstOrCreate(['id' => 1], ['title_fr' => 'À propos', 'title_en' => 'About']);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($about->image_path) Storage::disk('public')->delete($about->image_path);
            $data['image_path'] = $request->file('image')->store('about', 'public');
        }
        $data['is_published'] = $request->boolean('is_published', true);
        unset($data['image']);
        $about->update($data);
        return back()->with('success', 'About page updated.');
    }
}
