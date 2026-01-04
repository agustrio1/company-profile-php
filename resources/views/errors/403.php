<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
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

        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

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
            max-width: 480px;
            width: 100%;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
            text-align: center;
        }

        .code {
            font-size: 72px;
            font-weight: 800;
            color: var(--danger);
            line-height: 1;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 22px;
            color: var(--text-dark);
            margin: 0 0 12px;
        }

        p {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all .2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: .9;
        }

        .btn-outline {
            border-color: #e5e7eb;
            color: var(--text-dark);
            background: transparent;
        }

        .btn-outline:hover {
            background: #f3f4f6;
        }

        footer {
            margin-top: 30px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="card">
    <div class="code">403</div>

    <h1>Akses Ditolak</h1>

    <p>
        Anda tidak memiliki izin untuk mengakses halaman ini.
        Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
    </p>

    <div class="actions">
        <a href="<?= url('admin/dashboard') ?>" class="btn btn-primary">
            Kembali ke Dashboard
        </a>

        <a href="<?= url('/') ?>" class="btn btn-outline">
            Halaman Utama
        </a>
    </div>

    <footer>
        © <?= date('Y') ?> Company Name
    </footer>
</div>

</body>
</html>