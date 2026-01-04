<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>500 - Terjadi Kesalahan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary: #2563eb;
            --danger: #ef4444;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --bg: #f9fafb;
            --card: #ffffff;
        }

        * { box-sizing: border-box; font-family: system-ui, sans-serif; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: var(--card);
            max-width: 460px;
            width: 100%;
            padding: 42px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
            text-align: center;
        }

        .code {
            font-size: 72px;
            font-weight: 800;
            color: var(--danger);
            margin-bottom: 10px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        p {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 28px;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            padding: 12px 20px;
            background: var(--primary);
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        a:hover { opacity: .9; }
    </style>
</head>
<body>

<div class="card">
    <div class="code">500</div>

    <h1>Terjadi Kesalahan</h1>

    <p>
        Sistem sedang mengalami gangguan.
        Silakan coba beberapa saat lagi.
    </p>

    <a href="<?= url('/') ?>">
        Kembali ke Halaman Utama
    </a>
</div>

</body>
</html>