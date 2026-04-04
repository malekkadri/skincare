@csrf
<div class="grid">
    <div>
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', $adminUser->name ?? '') }}" required>
        @error('name')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $adminUser->email ?? '') }}" required>
        @error('email')<div class="error">{{ $message }}</div>@enderror
    </div>
</div>
<div class="grid">
    <div>
        <label>Password @if(isset($adminUser))<span class="muted">(Leave blank to keep current)</span>@endif</label>
        <input type="password" name="password" {{ isset($adminUser) ? '' : 'required' }}>
        @error('password')<div class="error">{{ $message }}</div>@enderror
    </div>
    <div>
        <label>Role</label>
        <select name="role" required>
            @foreach($roles as $key => $role)
                <option value="{{ $key }}" @selected(old('role', $adminUser->role ?? 'admin') === $key)>{{ $role['label'] ?? ucfirst(str_replace('_', ' ', $key)) }}</option>
            @endforeach
        </select>
        @error('role')<div class="error">{{ $message }}</div>@enderror
    </div>
</div>
<div style="margin-top: 1rem;">
    <label style="display: flex; align-items: center; gap: .5rem; font-weight: 500;">
        <input style="width: auto;" type="checkbox" name="is_active" value="1" @checked(old('is_active', $adminUser->is_active ?? true))>
        Active account
    </label>
</div>
<div style="margin-top: 1rem; display:flex; gap: .5rem;">
    <button type="submit" class="btn">Save User</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
</div>
