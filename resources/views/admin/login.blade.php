<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>SkillCraft — Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Inter',sans-serif;
  color:#fff;
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background:
    linear-gradient(135deg,rgba(5,10,20,0.80) 0%,rgba(10,18,35,0.75) 100%),
    url('/images/juang_bg.png') center center / cover no-repeat fixed;
}
.box{
  background:rgba(255,255,255,0.09);
  backdrop-filter:blur(24px);
  -webkit-backdrop-filter:blur(24px);
  border:1px solid rgba(255,255,255,0.16);
  border-radius:20px;
  padding:40px 36px;
  width:420px;
  max-width:95vw;
  animation:fadeUp .5s ease;
  box-shadow:0 20px 60px rgba(0,0,0,0.4);
}
.logo{display:flex;align-items:center;gap:12px;justify-content:center;margin-bottom:32px}
.logo-icon{width:46px;height:46px;border-radius:12px;object-fit:cover;box-shadow:0 0 0 2px rgba(96,165,250,0.4)}
.logo-text{font-family:'Poppins',sans-serif;font-size:23px;font-weight:800;color:#fff}
.logo-text span{color:#60a5fa}
h2{font-family:'Poppins',sans-serif;font-size:21px;font-weight:700;margin-bottom:5px;color:#fff}
.sub{font-size:13px;color:rgba(255,255,255,0.55);margin-bottom:28px}
.form-group{margin-bottom:16px}
.form-label{display:block;font-size:11px;color:rgba(255,255,255,0.60);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;font-family:'Poppins',sans-serif;font-weight:600}
.input-wrap{position:relative}
.form-input{width:100%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.16);border-radius:9px;padding:11px 42px 11px 14px;color:#fff;font-size:14px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s,background .2s}
.form-input::placeholder{color:rgba(255,255,255,0.30)}
.form-input:focus{border-color:#60a5fa;background:rgba(96,165,250,0.08)}
.toggle-pw{position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.45);display:flex;align-items:center;padding:0;transition:color .2s}
.toggle-pw:hover{color:#fff}
.toggle-pw svg{width:17px;height:17px}
.btn{width:100%;padding:12px;border-radius:9px;font-size:14px;font-weight:600;background:#60a5fa;color:#fff;border:none;cursor:pointer;font-family:'Poppins',sans-serif;margin-top:8px;transition:all .2s;letter-spacing:.2px}
.btn:hover{background:#3b82f6;transform:translateY(-1px);box-shadow:0 6px 20px rgba(96,165,250,0.35)}
.link{text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,0.50)}
.link a{color:#93c5fd;text-decoration:none;font-weight:500}
.link a:hover{color:#fff}
.alert-error{background:rgba(248,113,113,.12);border:1px solid rgba(248,113,113,.25);color:#fca5a5;padding:11px 14px;border-radius:9px;font-size:13px;margin-bottom:16px}
.alert-success{background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.25);color:#6ee7b7;padding:11px 14px;border-radius:9px;font-size:13px;margin-bottom:16px}
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>
</head>
<body>
<div class="box">
  <div class="logo">
    <img src="{{ asset('images/skillcraft_logo.jpg') }}" alt="SkillCraft Logo" class="logo-icon"/>
    <div class="logo-text">Skill<span>Craft</span></div>
  </div>
  <h2>Admin Login</h2>
  <p class="sub">Sign in to manage the game platform</p>

  @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <div class="form-group">
      <label class="form-label">Username</label>
      <input class="form-input" type="text" name="username" value="{{ old('username') }}" placeholder="Enter your username" required/>
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <div class="input-wrap">
        <input class="form-input" type="password" id="password" name="password" placeholder="••••••••" required/>
        <button type="button" class="toggle-pw" onclick="togglePw('password','eye1')">
          <svg id="eye1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
      </div>
    </div>
    <button type="submit" class="btn">Sign In</button>
  </form>
  <div class="link">No account yet? <a href="{{ route('admin.register') }}">Register Admin</a></div>
</div>
<script>
function togglePw(inputId,iconId){
  const input=document.getElementById(inputId);
  const icon=document.getElementById(iconId);
  const isHidden=input.type==='password';
  input.type=isHidden?'text':'password';
  icon.innerHTML=isHidden
    ?`<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`
    :`<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
}
</script>
</body>
</html>
