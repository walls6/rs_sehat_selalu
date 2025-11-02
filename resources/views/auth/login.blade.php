<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Masuk dengan Gmail - RS Sehat Selalu</title>
  <style>
    :root{
      --bg:#f5f7fb;
      --card:#ffffff;
      --muted:#6b7280;
      --accent:#1a73e8; /* google blue hint */
      --shadow: 0 8px 30px rgba(23,25,32,0.08);
      --radius:14px;
      --gap:18px;
      --success:#16a34a;
      --error:#dc2626;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: linear-gradient(180deg, #eef2ff 0%, var(--bg) 100%);
      display:flex;
      align-items:center;
      justify-content:center;
      padding:32px;
      color:#0f172a;
    }
    .wrap{
      width:100%;
      max-width:420px;
      padding:28px;
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      display:flex;
      flex-direction:column;
      gap:var(--gap);
      align-items:stretch;
    }
    .brand{
      display:flex;
      gap:12px;
      align-items:center;
    }
    .logo{
      width:48px;
      height:48px;
      display:inline-grid;
      place-items:center;
      border-radius:10px;
      background: linear-gradient(45deg,#fff,#f8fafc);
      box-shadow: 0 2px 6px rgba(16,24,40,0.06);
      flex-shrink:0;
    }
    .brand h1{
      font-size:18px;
      margin:0;
      letter-spacing:-0.2px;
    }
    .brand p{
      margin:0;
      font-size:13px;
      color:var(--muted);
    }
    .desc{
      font-size:14px;
      color:var(--muted);
      line-height:1.5;
      margin-top:6px;
    }
    .google-btn{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:12px;
      padding:12px 14px;
      border-radius:12px;
      border:1px solid rgba(16,24,40,0.06);
      background: #fff;
      cursor:pointer;
      font-weight:600;
      font-size:15px;
      transition:transform .12s ease, box-shadow .12s ease;
      box-shadow: 0 1px 0 rgba(16,24,40,0.04);
      text-decoration:none;
      color: #202124;
      outline: none;
      width:100%;
    }
    .google-btn:active{ transform: translateY(1px); }
    .google-btn:hover{ box-shadow: 0 6px 18px rgba(16,24,40,0.08); }
    .google-btn:focus{ box-shadow: 0 0 0 4px rgba(26,115,232,0.12); }
    .g-icon{
      width:20px;
      height:20px;
      display:inline-block;
      flex-shrink:0;
    }
    .divider{
      text-align:center;
      color:var(--muted);
      font-size:13px;
      margin:4px 0 6px;
      position:relative;
    }
    .divider::before,
    .divider::after{
      content:'';
      position:absolute;
      top:50%;
      width:calc(50% - 30px);
      height:1px;
      background:rgba(16,24,40,0.1);
    }
    .divider::before{
      left:0;
    }
    .divider::after{
      right:0;
    }
    .terms{
      font-size:12px;
      color:var(--muted);
      line-height:1.4;
    }
    .field{
      display:flex;
      flex-direction:column;
      gap:8px;
    }
    .input{
      padding:12px 14px;
      border-radius:10px;
      border:1px solid rgba(16,24,40,0.06);
      background:#fff;
      font-size:14px;
      outline:none;
      width:100%;
    }
    .input:focus{ box-shadow: 0 0 0 4px rgba(26,115,232,0.08); border-color: rgba(26,115,232,0.18); }
    .helper{
      font-size:13px;
      color:var(--muted);
    }
    footer{
      display:flex;
      justify-content:center;
      margin-top:8px;
      font-size:13px;
      color:var(--muted);
    }
    .alert{
      padding:12px 16px;
      border-radius:10px;
      margin-bottom:16px;
      font-size:14px;
    }
    .alert-success{
      background:#dcfce7;
      color:#166534;
      border:1px solid #86efac;
    }
    .alert-error{
      background:#fee2e2;
      color:#991b1b;
      border:1px solid #fca5a5;
    }
    @media (max-width:420px){
      .wrap{ padding:18px; margin: 0 12px; }
      .brand h1{ font-size:16px; }
    }
  </style>
</head>
<body>
  <main class="wrap" role="main" aria-labelledby="title">
    <div class="brand" aria-hidden="false">
      <div class="logo" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Google">
          <path d="M44.5 20H24v8.5h11.9C34.7 33.9 30 37 24 37c-7.7 0-14-6.3-14-14s6.3-14 14-14c3.6 0 6.8 1.3 9.3 3.6l6.6-6.6C36.6 2.7 30.6 0 24 0 10.7 0 0 10.7 0 24s10.7 24 24 24c13.3 0 24-10.7 24-24 0-1.6-.2-3.1-.5-4.5z" fill="#4285F4"/>
        </svg>
      </div>
      <div>
        <h1 id="title">Masuk ke Aplikasi</h1>
        <p>Masuk cepat menggunakan akun Google Anda</p>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    <p class="desc">Gunakan akun Google untuk masuk tanpa perlu membuat kata sandi. Aman dan cepat.</p>

    <div style="display:flex;flex-direction:column;gap:10px;">
      <a href="{{ route('google.login') }}" id="googleSignIn" class="google-btn" aria-label="Masuk dengan Google">
        <span class="g-icon" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" role="img">
            <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C34.7 33.9 30 37 24 37c-7.7 0-14-6.3-14-14s6.3-14 14-14c3.6 0 6.8 1.3 9.3 3.6l6.6-6.6C36.6 2.7 30.6 0 24 0 10.7 0 0 10.7 0 24s10.7 24 24 24c13.2 0 24-10.7 24-24 0-1.6-.2-3.1-.5-4.5z"/>
            <path fill="#FF3D00" d="M6.3 14.7l6.1 4.5C14.6 16.1 19 13 24 13c3.6 0 6.8 1.3 9.3 3.6l6.6-6.6C36.6 2.7 30.6 0 24 0 16.8 0 10.6 3.6 6.3 9.3z"/>
            <path fill="#4CAF50" d="M24 48c6.1 0 11.6-2.1 15.9-5.7l-7.3-5.9C29.9 36.9 27 38 24 38c-6 0-10.7-3.1-13.7-7.6l-6.1 4.7C6.4 41.9 14.8 48 24 48z"/>
            <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-1.1 3.1-3.2 5.7-6.1 7.4 0 0 0-.1 7.3-5.9C40.2 30.8 43.6 26 43.6 20.5z"/>
          </svg>
        </span>
        <span>Masuk dengan Google</span>
      </a>
    </div>

    <p class="terms">Dengan masuk, Anda menyetujui <a href="#" style="color:inherit;text-decoration:underline">Syarat & Ketentuan</a> dan <a href="#" style="color:inherit;text-decoration:underline">Kebijakan Privasi</a>.</p>

    <footer>Butuh bantuan? <a href="#" style="color:var(--accent);text-decoration:none;margin-left:6px">Hubungi support</a></footer>
  </main>
</body>
</html>

