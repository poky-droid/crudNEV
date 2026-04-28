<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Belajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #0d0d0f;
            --surface: #16161a;
            --border: #2a2a35;
            --accent: #7c6af7;
            --text: #e8e8f0;
            --text-muted: #7a7a95;
            --danger: #f76a6a;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'DM Sans',sans-serif;
            background:var(--bg);
            color:var(--text);
            min-height:100vh;
            display:grid;
            grid-template-columns:1fr 1fr;
        }

        .left-panel{
            background:var(--surface);
            border-right:1px solid var(--border);
            padding:60px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }

        .brand h1{
            font-family:'Syne',sans-serif;
            font-size:38px;
            font-weight:800;
        }

        .brand span{
            color:var(--accent);
        }

        .brand p{
            margin-top:8px;
            font-size:13px;
            letter-spacing:2px;
            color:var(--text-muted);
            text-transform:uppercase;
        }

        .tagline{
            margin-top:60px;
        }

        .tagline h2{
            font-family:'Syne',sans-serif;
            font-size:30px;
            line-height:1.3;
            margin-bottom:14px;
        }

        .tagline p{
            color:var(--text-muted);
            max-width:350px;
            line-height:1.7;
        }

        .features{
            margin-top:45px;
            display:flex;
            flex-direction:column;
            gap:14px;
        }

        .feature-item{
            color:var(--text-muted);
            font-size:14px;
        }

        .right-panel{
            display:flex;
            justify-content:center;
            align-items:center;
            padding:60px;
        }

        .login-box{
            width:100%;
            max-width:380px;
        }

        .login-box h3{
            font-family:'Syne',sans-serif;
            font-size:24px;
            margin-bottom:8px;
        }

        .sub{
            color:var(--text-muted);
            margin-bottom:35px;
            font-size:14px;
        }

        .form-group{
            margin-bottom:20px;
        }

        label{
            display:block;
            font-size:12px;
            margin-bottom:8px;
            color:var(--text-muted);
            text-transform:uppercase;
            letter-spacing:1px;
        }

        .input-wrap{
            position:relative;
        }

        .input-wrap i{
            position:absolute;
            left:14px;
            top:50%;
            transform:translateY(-50%);
            color:var(--text-muted);
        }

        input{
            width:100%;
            padding:13px 14px 13px 40px;
            background:rgba(255,255,255,.04);
            border:1px solid var(--border);
            border-radius:10px;
            color:var(--text);
            outline:none;
        }

        input:focus{
            border-color:var(--accent);
        }

        .btn-login{
            width:100%;
            border:none;
            padding:14px;
            border-radius:10px;
            background:var(--accent);
            color:white;
            font-family:'Syne',sans-serif;
            font-weight:700;
            cursor:pointer;
            margin-top:10px;
        }

        .alert-danger{
            background:rgba(247,106,106,.08);
            border:1px solid rgba(247,106,106,.2);
            color:var(--danger);
            padding:12px;
            border-radius:8px;
            margin-bottom:20px;
            font-size:13px;
        }

        @media(max-width:768px){
            body{
                grid-template-columns:1fr;
            }

            .left-panel{
                display:none;
            }

            .right-panel{
                padding:30px;
            }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="brand">
        <h1>Belajar<span>.</span></h1>
        <p>Admin Panel</p>
    </div>

    <div class="tagline">
        <h2>Kelola data organisasi dengan mudah dan efisien.</h2>
        <p>Platform manajemen anggota, modul, dan konten untuk komunitas belajar Anda.</p>
    </div>

    <div class="features">
        <div class="feature-item">• Manajemen Anggota & Divisi</div>
        <div class="feature-item">• Pengelolaan News & Modul</div>
        <div class="feature-item">• Data Sponsor Terstruktur</div>
    </div>
</div>

<div class="right-panel">
    <div class="login-box">

        <h3>Selamat datang kembali</h3>
        <p class="sub">Masuk menggunakan Email dan password Anda</p>

        @if($errors->any())
            <div class="alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Masuk →
            </button>
        </form>

    </div>
</div>

</body>
</html>