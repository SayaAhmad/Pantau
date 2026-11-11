<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan akun · PantauWeb</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f3f7f4;color:#14241e;font:14px 'DM Sans',sans-serif}.wrap{max-width:760px;margin:0 auto;padding:34px 22px}.brand{font:800 19px Manrope,sans-serif;text-decoration:none;color:inherit}.brand span{color:#176b50}.back{display:block;color:#176b50;text-decoration:none;font-size:12px;font-weight:700;margin:30px 0 18px}.card{background:white;border:1px solid #e4ece7;border-radius:17px;padding:26px}.eyebrow{font-size:10px;letter-spacing:1.4px;color:#176b50;font-weight:800}.card h1{font:800 26px Manrope,sans-serif;letter-spacing:-.8px;margin:8px 0}.sub{color:#728078;font-size:12px;margin:0 0 22px}.field{margin:15px 0}.field label{display:block;font-size:11px;font-weight:700;margin-bottom:7px}.field input{width:100%;border:1px solid #dfe7e2;border-radius:9px;padding:12px;outline:0}.btn{border:0;border-radius:9px;background:#176b50;color:white;font-weight:700;padding:11px 16px;cursor:pointer}.alert{padding:11px;border-radius:9px;background:#e9f6ee;color:#176b50;font-size:12px;margin-bottom:14px}.alert.error{background:#fff0ee;color:#a83c35}.section{border-top:1px solid #edf1ef;margin-top:25px;padding-top:19px}.section h2{font:700 14px Manrope;margin:0}.section p{color:#728078;font-size:11px;line-height:1.6}
    </style>
</head>
<body>
    <main class="wrap">
        <a class="brand" href="{{ route('dashboard') }}">Pantau<span>Web</span></a>
        <a class="back" href="{{ route('dashboard') }}">← Kembali ke dashboard</a>
        <section class="card">
            <div class="eyebrow">PREFERENSI</div>
            <h1>Pengaturan akun</h1>
            <p class="sub">Kelola identitas dan kata sandi akun PantauWeb.</p>

            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('account.update') }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
                </div>

                <div class="section">
                    <h2>Ubah kata sandi</h2>
                    <p>Kosongkan jika tidak ingin mengganti kata sandi.</p>

                    <div class="field">
                        <label for="password">Kata sandi baru</label>
                        <input id="password" name="password" type="password" autocomplete="new-password">
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Ulangi kata sandi baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    </div>
                </div>

                <button class="btn">Simpan pengaturan</button>
            </form>
        </section>
    </main>
</body>
</html>
