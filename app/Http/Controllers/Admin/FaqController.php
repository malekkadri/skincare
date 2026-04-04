<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class FaqController extends Controller
{
    public function index(): View { return view('admin.faq.index', ['items' => Faq::ordered()->paginate(20)]); }
    public function create(): View { return view('admin.faq.create', ['item' => new Faq()]); }
    public function store(StoreFaqRequest $request): RedirectResponse { $data=$request->validated();$data['is_active']=$request->boolean('is_active',true);Faq::create($data);return to_route('admin.faq.index')->with('success','FAQ created.'); }
    public function edit(Faq $faq): View { return view('admin.faq.edit', ['item' => $faq]); }
    public function update(UpdateFaqRequest $request, Faq $faq): RedirectResponse { $data=$request->validated();$data['is_active']=$request->boolean('is_active');$faq->update($data);return back()->with('success','FAQ updated.'); }
    public function destroy(Faq $faq): RedirectResponse { $faq->delete(); return back()->with('success','FAQ deleted.'); }
}
