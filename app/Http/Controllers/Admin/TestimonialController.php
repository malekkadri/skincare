<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class TestimonialController extends Controller
{
    public function index(): View { return view('admin.testimonials.index', ['items' => Testimonial::with('service')->ordered()->paginate(20)]); }
    public function create(): View { return view('admin.testimonials.create', ['item' => new Testimonial(), 'services' => Service::ordered()->get()]); }
    public function store(StoreTestimonialRequest $request): RedirectResponse { $data=$request->validated();$data['is_featured']=$request->boolean('is_featured');$data['is_active']=$request->boolean('is_active',true);Testimonial::create($data);return to_route('admin.testimonials.index')->with('success','Testimonial created.'); }
    public function edit(Testimonial $testimonial): View { return view('admin.testimonials.edit', ['item' => $testimonial, 'services' => Service::ordered()->get()]); }
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial): RedirectResponse { $data=$request->validated();$data['is_featured']=$request->boolean('is_featured');$data['is_active']=$request->boolean('is_active');$testimonial->update($data);return back()->with('success','Testimonial updated.'); }
    public function destroy(Testimonial $testimonial): RedirectResponse { $testimonial->delete(); return back()->with('success','Testimonial deleted.'); }
}
