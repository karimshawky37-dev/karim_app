<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - <?php echo APP_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        body { 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 40px 36px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }
        .login-card .logo {
            text-align: center;
            font-size: 48px;
            margin-bottom: 8px;
        }
        .login-card h1 {
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .login-card .subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 28px;
        }
        .login-card .input-group {
            margin-bottom: 16px;
        }
        .login-card .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .login-card .input-group input {
            width: 100%;
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: 0.3s;
            outline: none;
        }
        .login-card .input-group input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }
        .login-card .input-group input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }
        .login-card .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 8px;
        }
        .login-card .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37,99,235,0.3);
        }
        .login-card .btn-login i { margin-left: 8px; }
        .login-card .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 10px;
            border-right: 4px solid #dc2626;
            margin-bottom: 16px;
            font-size: 13px;
        }
        .login-card .demo-info {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .login-card .demo-info strong { color: #64748b; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">🔧</div>
        <h1><?php echo APP_NAME; ?></h1>
        <p class="subtitle">سجل دخولك للوصول إلى لوحة التحكم</p>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box">
                <i class="fas fa-exclamation-circle ml-2"></i>
                اسم المستخدم أو كلمة المرور غير صحيحة
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['logout'])): ?>
            <div style="background:#d1fae5; color:#065f46; padding:10px 14px; border-radius:10px; border-right:4px solid #059669; margin-bottom:16px; font-size:13px;">
                <i class="fas fa-check-circle ml-2"></i>
                تم تسجيل الخروج بنجاح
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login-submit">
            <div class="input-group">
                <label><i class="fas fa-user ml-2"></i> اسم المستخدم</label>
                <input type="text" name="username" placeholder="أدخل اسم المستخدم" required autofocus>
            </div>
            <div class="input-group">
                <label><i class="fas fa-lock ml-2"></i> كلمة المرور</label>
                <input type="password" name="password" placeholder="أدخل كلمة المرور" required>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
            </button>
        </form>
        
        <div class="demo-info">
            <strong>بيانات الدخول التجريبية:</strong><br>
            مدير: <code>admin</code> / <code>admin123</code> |
            فني: <code>tech1</code> / <code>tech123</code> |
            محاسب: <code>accountant</code> / <code>password</code>
        </div>
    </div>
</body>
</html>