<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">

  <style>
    :root{
      --bg:#3380c7;           /* warna biru muda latar (boleh kamu sesuaikan) */
      --card-bg:#ffffff;
      --text:#1b2b3a;
      --muted:#7a8a9a;
      --brand:#1e73ff;        /* biru tombol */
      --radius:28px;
      --shadow:0 18px 45px rgba(0,0,0,.15);
    }

    *{ box-sizing: border-box; }

    body{
      margin:0;
      min-height:100vh;
      display:grid;
      place-items:center;
      background: radial-gradient(90rem 90rem at 50% -10%, #8ee6f5 0%, var(--bg) 40%, #33b7cf 100%);
      font-family: system-ui,-apple-system,Segoe UI,Roboto,Inter,Helvetica,Arial,sans-serif;
      color:var(--text);
    }

    /* Logo kecil di atas kartu */
    .mini-logo{
      width:52px; height:52px;
      border-radius:12px;
      background:#fff;
      display:grid; place-items:center;
      box-shadow: 0 10px 24px rgba(0,0,0,.12);
      margin: -24px auto 16px;
      transform: translateY(-16px);
    }
    .mini-logo img{ width:36px; height:36px; object-fit:contain; }

    .login-card{
      width:min(92vw, 420px);
      background:var(--card-bg);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:32px 28px 26px;
      position:relative;
    }

    .login-card .avatar{
      width:72px; height:72px;
      border-radius:18px;
      display:grid; place-items:center;
      margin:0 auto 10px;
      background:#eaf2ff;
      color:var(--brand);
      font-size:40px;
    }

    .login-card h1{
      font-size:22px;
      text-align:center;
      margin:0 0 18px;
      color:var(--brand);
      font-weight:700;
      letter-spacing:.3px;
    }

    .field{
      margin-bottom:14px;
      position:relative;
    }

    .field .input{
      width:100%;
      height:48px;
      border-radius:14px;
      border:2px solid #e6eef6;
      background:#f4f8fc;
      padding:0 44px 0 44px;     /* ruang untuk ikon kiri & kanan */
      font-size:14.5px;
      outline:none;
      transition:.18s ease;
      color:#1f2d3a;
    }
    .field .input:focus{
      border-color:#cfe2ff;
      background:#fff;
      box-shadow: 0 0 0 3px rgba(30,115,255,.08);
    }

    .field .left-icon,
    .field .right-icon{
      position:absolute; top:50%; transform:translateY(-50%);
      font-size:18px;
      color:#8aa0b4;
    }
    .field .left-icon{ left:14px; }
    .field .right-icon{ right:14px; cursor:pointer; }

    .btn-primary{
      width:100%;
      height:48px;
      border:none;
      border-radius:14px;
      background:var(--brand);
      color:#fff;
      font-weight:700;
      letter-spacing:.6px;
      cursor:pointer;
      transition: transform .04s ease, box-shadow .2s ease;
      box-shadow: 0 10px 24px rgba(30,115,255,.35);
    }
    .btn-primary:active{ transform: translateY(1px); }

    .extra-link{
      display:block;
      text-align:center;
      margin-top:10px;
      color:var(--muted);
      text-decoration:none;
      font-size:13.5px;
    }
    .error-message{ color:#e02424; font-size:12px; margin-top:6px; display:block; }
    .is-invalid{ border-color:#e02424 !important; background:#fff5f5 !important; }
  </style>
</head>
<body>

  <div class="login-card">

    <!-- Logo kecil di atas kartu (opsional) -->
    <div class="mini-logo">
      <img src="{{ asset('asset_halaman_desa/img/pln.png') }}" alt="Logo">
    </div>

    <div class="avatar">
      <i class='bx bxs-user'></i>
    </div>
    <h1>User Login</h1>

    <form id="loginForm" method="POST" action="{{ route('login.submit') }}">
      @csrf

      <div class="field">
        <i class='bx bx-user left-icon'></i>
        <input
          type="email"
          name="email"
          class="input"
          placeholder="user"
          value="{{ old('email') }}"
          required>
      </div>

      <div class="field">
        <i class='bx bx-key left-icon'></i>
        <input
          type="password"
          name="password"
          class="input password"
          placeholder="password"
          required minlength="5">
        <i class='bx bx-hide right-icon eye-icon'></i>
      </div>

      <button type="submit" class="btn-primary">LOGIN</button>
    </form>

    <a href="{{ Route::has('landing') ? route('landing') : url('/') }}" class="extra-link">Home</a>
  </div>

  <!-- jQuery -->
  <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery Validation -->
  <script src="{{ asset('asset_halaman_desa/adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Toggle show/hide password
    (function () {
      const eye = document.querySelector('.eye-icon');
      const pwd = document.querySelector('input.password');
      if (eye && pwd) {
        eye.addEventListener('click', function () {
          const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
          pwd.setAttribute('type', type);
          this.classList.toggle('bx-hide');
          this.classList.toggle('bx-show');
        });
      }
    })();

    // jQuery Validation
    $(function () {
      $("#loginForm").validate({
        rules: {
          email: { required: true, email: true },
          password: { required: true, minlength: 5 }
        },
        messages: {
          email: { required: "Email wajib diisi", email: "Format email tidak valid" },
          password: { required: "Password wajib diisi", minlength: "Password minimal 5 karakter" }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
          error.addClass("error-message");
          element.closest(".field").append(error);
        },
        highlight: function (element) { $(element).addClass("is-invalid"); },
        unhighlight: function (element) { $(element).removeClass("is-invalid"); }
      });
    });

    // SweetAlert dari server
    @if ($errors->any())
      let errorMessages = {!! json_encode($errors->all()) !!};
      Swal.fire({ icon: 'error', title: 'Login Gagal', html: errorMessages.join('<br>'), confirmButtonColor: '#d33' });
    @endif
    @if (session('success'))
      Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')) });
    @endif
  </script>
</body>
</html>
