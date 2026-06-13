<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            overflow: hidden;
            position: relative;
        }
        
        /* Decorative circles */
        .glass-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.5;
        }
        
        .glass-card::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.5;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h3 {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #718096;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
            background-color: #ffffff;
        }

        .input-group-text {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 10;
            background: transparent;
            border: none;
            color: #a0aec0;
            padding-left: 15px;
            display: flex;
            align-items: center;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(118, 75, 162, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(118, 75, 162, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="glass-card">
        <div class="login-header">
            <div class="mb-3">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; display: inline-flex; align-items: center; justify-content: center; transform: rotate(-10deg);">
                    <i class="bi bi-book-half text-white fs-1" style="transform: rotate(10deg);"></i>
                </div>
            </div>
            <h3>Library Admin</h3>
            <p>Welcome back! Please login to your account.</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 rounded-3 shadow-sm d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>
        
        <?php if(isset($validation)): ?>
            <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <div class="mb-4 position-relative">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email Address" required value="<?= set_value('email') ?>">
            </div>
            
            <div class="mb-4 position-relative">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            
            <button type="submit" class="btn btn-login w-100">
                Sign In <i class="bi bi-arrow-right-circle ms-1"></i>
            </button>
        </form>
    </div>
</body>
</html>
