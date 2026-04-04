<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePolicyRequest;
use App\Http\Requests\Admin\UpdatePolicyRequest;
use App\Models\Policy;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class PolicyController extends Controller
{
    public function index(): View { return view('admin.policies.index', ['items' => Policy::ordered()->paginate(20)]); }
    public function create(): View { return view('admin.policies.create', ['item' => new Policy()]); }
    public function store(StorePolicyRequest $request): RedirectResponse { $data=$request->validated();$data['is_active']=$request->boolean('is_active',true);Policy::create($data);return to_route('admin.policies.index')->with('success','Policy created.'); }
    public function edit(Policy $policy): View { return view('admin.policies.edit', ['item' => $policy]); }
    public function update(UpdatePolicyRequest $request, Policy $policy): RedirectResponse { $data=$request->validated();$data['is_active']=$request->boolean('is_active');$policy->update($data);return back()->with('success','Policy updated.'); }
    public function destroy(Policy $policy): RedirectResponse { $policy->delete();return back()->with('success','Policy deleted.'); }
}
