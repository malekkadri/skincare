<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertHomepageSectionRequest;
use App\Models\HomepageSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomepageSectionController extends Controller
{
    public function index(): View { return view('admin.homepage.index', ['sections' => HomepageSection::ordered()->get()]); }
    public function edit(HomepageSection $homepage): View { return view('admin.homepage.edit', ['section' => $homepage]); }
    public function update(UpsertHomepageSectionRequest $request, HomepageSection $homepage): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($homepage->image_path) Storage::disk('public')->delete($homepage->image_path);
            $data['image_path'] = $request->file('image')->store('homepage', 'public');
        }
        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);
        $homepage->update($data);
        return back()->with('success', 'Homepage section updated.');
    }
}
