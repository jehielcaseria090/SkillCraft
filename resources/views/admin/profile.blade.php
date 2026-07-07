@extends('admin.layout')
@section('title','Profile')
@section('content')

<div class="topbar">
  <div class="page-title">Admin <span>Profile</span></div>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start">

  {{-- Left: Profile Picture --}}
  <div class="panel" style="overflow:visible">
    <div style="padding:32px 24px;text-align:center">

      {{-- Avatar --}}
      <div style="position:relative;display:inline-block;margin-bottom:20px">
        @if($admin->profile_picture)
          <img id="avatar-preview"
               src="{{ asset('storage/'.$admin->profile_picture) }}"
               alt="Profile"
               style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);display:block"/>
        @else
          <div id="avatar-initials"
               style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:40px;font-weight:800;color:#fff;margin:0 auto">
            {{ strtoupper(substr($admin->first_name,0,1).substr($admin->last_name,0,1)) }}
          </div>
          <img id="avatar-preview"
               src="" alt="Preview"
               style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);display:none"/>
        @endif

        {{-- Camera button --}}
        <label for="picture-input"
               title="Change profile picture"
               style="position:absolute;bottom:4px;right:4px;width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;cursor:pointer;border:2px solid var(--bg);transition:background .2s"
               onmouseover="this.style.background='#3a7ae0'"
               onmouseout="this.style.background='var(--accent)'">
          <svg style="width:15px;height:15px;color:#fff" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
            <circle cx="12" cy="13" r="4"/>
          </svg>
        </label>
      </div>

      {{-- Name and role --}}
      <div style="font-family:'Syne',sans-serif;font-size:19px;font-weight:700;margin-bottom:4px">
        {{ $admin->first_name }} {{ $admin->last_name }}
      </div>
      <div style="font-size:12px;color:var(--muted);margin-bottom:4px">
        {{ $admin->email }}
      </div>
      <div style="font-size:12px;color:var(--muted);margin-bottom:16px">
        @{{ $admin->username }}
      </div>
      <span class="badge blue">Administrator</span>

      {{-- Hidden upload form --}}
      <form method="POST"
            action="{{ route('profile.picture') }}"
            enctype="multipart/form-data"
            id="picture-form"
            style="margin-top:0">
        @csrf
        <input type="file"
               id="picture-input"
               name="profile_picture"
               accept="image/jpg,image/jpeg,image/png,image/webp"
               style="display:none"
               onchange="previewAndSubmit(this)"/>
      </form>

      {{-- Remove button --}}
      @if($admin->profile_picture)
      <form method="POST"
            action="{{ route('profile.picture.remove') }}"
            onsubmit="return confirm('Remove your profile picture?')"
            style="margin-top:16px">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
          Remove Picture
        </button>
      </form>
      @endif

      <div style="font-size:11px;color:var(--muted);margin-top:16px;line-height:1.6">
        JPG, PNG or WEBP<br/>Maximum size: 2MB<br/>Click the camera icon to upload
      </div>
    </div>

    {{-- Account info summary --}}
    <div style="border-top:1px solid var(--border);padding:18px 24px">
      <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);margin-bottom:12px">
        Account Details
      </div>
      <div style="display:flex;flex-direction:column;gap:10px">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:12px;color:var(--muted)">Role</span>
          <span class="badge blue">Admin</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:12px;color:var(--muted)">Contact</span>
          <span style="font-size:13px">{{ $admin->contact_number ?? '—' }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:12px;color:var(--muted)">Member since</span>
          <span style="font-size:12px;color:var(--muted)">{{ $admin->created_at->format('M d, Y') }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Right: Forms --}}
  <div>

    {{-- Personal Information --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">Personal Information</div>
      </div>
      <div style="padding:24px">
        <form method="POST" action="{{ route('profile.info') }}">
          @csrf
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input class="form-input" type="text" name="first_name"
                     value="{{ old('first_name', $admin->first_name) }}" required/>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input class="form-input" type="text" name="last_name"
                     value="{{ old('last_name', $admin->last_name) }}" required/>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input class="form-input" type="email" name="email"
                   value="{{ old('email', $admin->email) }}" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Username</label>
            <input class="form-input" type="text"
                   value="{{ $admin->username }}" disabled
                   style="opacity:.45;cursor:not-allowed"/>
            <div style="font-size:11px;color:var(--muted);margin-top:5px">
              Username cannot be changed after registration
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Contact Number</label>
            <input class="form-input" type="text" name="contact_number"
                   value="{{ old('contact_number', $admin->contact_number) }}"
                   placeholder="e.g. 09123456789"/>
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">Change Password</div>
      </div>
      <div style="padding:24px">
        <form method="POST" action="{{ route('profile.password') }}">
          @csrf
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input class="form-input" type="password" name="current_password" required
                   placeholder="Enter your current password"/>
            @error('current_password')
              <div style="font-size:12px;color:#f74f4f;margin-top:5px">{{ $message }}</div>
            @enderror
          </div>
          <div class="grid-2">
            <div class="form-group">
              <label class="form-label">New Password</label>
              <input class="form-input" type="password" name="password" required
                     placeholder="Min. 8 characters"/>
            </div>
            <div class="form-group">
              <label class="form-label">Confirm New Password</label>
              <input class="form-input" type="password" name="password_confirmation" required
                     placeholder="Repeat new password"/>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
function previewAndSubmit(input) {
  if (!input.files || !input.files[0]) return;

  const file = input.files[0];

  if (file.size > 2 * 1024 * 1024) {
    alert('File is too large. Maximum allowed size is 2MB.');
    input.value = '';
    return;
  }

  const reader = new FileReader();
  reader.onload = function(e) {
    const preview  = document.getElementById('avatar-preview');
    const initials = document.getElementById('avatar-initials');
    preview.src = e.target.result;
    preview.style.display = 'block';
    if (initials) initials.style.display = 'none';
  };
  reader.readAsDataURL(file);

  // Auto submit after preview loads
  setTimeout(() => {
    document.getElementById('picture-form').submit();
  }, 400);
}
</script>
@endsection
