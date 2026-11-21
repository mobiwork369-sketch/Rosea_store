<?php
require_once __DIR__ . '/includes/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pw    = $_POST['password'] ?? '';
    $pw2   = $_POST['password_confirm'] ?? '';

    if (mb_strlen($name) < 3) {
        $error = 'نام و نام خانوادگی باید حداقل ۳ حرف باشد.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'ایمیل وارد شده معتبر نیست.';
    } elseif (!preg_match('/^09\d{9}$/', $phone)) {
        $error = 'شماره موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.';
    } elseif (strlen($pw) < 8) {
        $error = 'رمز عبور باید حداقل ۸ کاراکتر باشد.';
    } elseif ($pw !== $pw2) {
        $error = 'تکرار رمز عبور با رمز عبور یکسان نیست.';
    } else {
        try {
            $st = $pdo->prepare("INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
            $st->execute([
                $name,
                $email,
                $phone,
                password_hash($pw, PASSWORD_DEFAULT)
            ]);

            $_SESSION['user'] = [
                'id'    => $pdo->lastInsertId(),
                'name'  => $name,
                'email' => $email,
                'role'  => 'customer'
            ];

            header('Location: account.php');
            exit;
        } catch (PDOException $e) {
            $error = 'این ایمیل قبلاً ثبت شده یا اطلاعات نامعتبر است.';
        }
    }
}

$pageTitle = 'ثبت‌نام در رُزآرا';
include __DIR__ . '/includes/header.php';
?>

<main class="page-content auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-icon">❀</span>
            <h1>عضویت در رُزآرا</h1>
            <p class="auth-subtitle">به خانواده زیبایی رُزآرا خوش آمدید</p>
        </div>

        <?php if ($error): ?>
            <div class="notice notice-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post" novalidate>
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

            <div class="field">
                <label for="name">نام و نام خانوادگی</label>
                <input id="name" name="name" type="text" autocomplete="name" required
                       value="<?= e($_POST['name'] ?? '') ?>" placeholder="مثلاً سارا محمدی">
            </div>

            <div class="field">
                <label for="email">ایمیل</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                       value="<?= e($_POST['email'] ?? '') ?>" placeholder="example@email.com">
            </div>

            <div class="field">
                <label for="phone">شماره موبایل</label>
                <input id="phone" name="phone" type="tel" autocomplete="tel" required
                       value="<?= e($_POST['phone'] ?? '') ?>" placeholder="۰۹۱۲۳۴۵۶۷۸۹">
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">رمز عبور</label>
                    <input id="password" name="password" type="password" minlength="8"
                           autocomplete="new-password" required placeholder="حداقل ۸ کاراکتر">
                </div>
                <div class="field">
                    <label for="password_confirm">تکرار رمز عبور</label>
                    <input id="password_confirm" name="password_confirm" type="password" minlength="8"
                           autocomplete="new-password" required placeholder="تکرار رمز">
                </div>
            </div>

            <div class="field-check">
                <label>
                    <input type="checkbox" name="terms" required>
                    <span>قوانین و شرایط استفاده و <a href="guide.php#privacy">حریم خصوصی</a> را می‌پذیرم</span>
                </label>
            </div>

            <button type="submit" class="btn-auth">ایجاد حساب کاربری</button>
        </form>

        <div class="auth-footer">
            قبلاً ثبت‌نام کرده‌اید؟
            <a href="login.php">وارد شوید</a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
