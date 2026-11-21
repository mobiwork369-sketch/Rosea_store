<?php
require_once __DIR__ . '/includes/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');
    $pw    = $_POST['password'] ?? '';

    $st = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $st->execute([$email]);
    $u = $st->fetch();

    if ($u && password_verify($pw, $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'    => $u['id'],
            'name'  => $u['full_name'],
            'email' => $u['email'],
            'role'  => $u['role']
        ];

        $redirect = (($u['role'] ?? '') === 'admin') ? 'admin/index.php' : 'account.php';
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'ایمیل یا رمز عبور صحیح نیست.';
    }
}

$pageTitle = 'ورود به رُزآرا';
include __DIR__ . '/includes/header.php';
?>

<main class="page-content auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-icon">❀</span>
            <h1>ورود به حساب کاربری</h1>
            <p class="auth-subtitle">به رُزآرا خوش آمدید</p>
        </div>

        <?php if ($error): ?>
            <div class="notice notice-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post" novalidate>
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

            <div class="field">
                <label for="email">ایمیل</label>
                <input id="email" name="email" type="email" autocomplete="username" required
                       value="<?= e($_POST['email'] ?? '') ?>" placeholder="example@email.com">
            </div>

            <div class="field">
                <label for="password">رمز عبور</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       placeholder="رمز عبور خود را وارد کنید">
            </div>

            <div class="field-row-between">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>مرا به خاطر بسپار</span>
                </label>
                <a href="#" class="forgot-link">فراموشی رمز عبور؟</a>
            </div>

            <button type="submit" class="btn-auth">ورود به حساب</button>
        </form>

        <div class="auth-footer">
            حساب کاربری ندارید؟
            <a href="register.php">ثبت‌نام کنید</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
