<?php
require_once __DIR__ . '/../includes/bootstrap.php';
if (!is_admin()) { header('Location: ../login.php'); exit; }
$customers = $pdo->query("SELECT u.id,u.full_name,u.email,u.phone,u.created_at,COUNT(o.id) AS order_count,COALESCE(SUM(o.total_amount),0) AS total_spent FROM users u LEFT JOIN orders o ON o.user_id=u.id WHERE u.role='customer' GROUP BY u.id ORDER BY u.created_at DESC")->fetchAll();
$pageTitle = 'مدیریت مشتریان | پنل رُزآرا';
include __DIR__ . '/../includes/header.php';
?>
<main class="page-content container">
  <h1 class="page-title">مدیریت مشتریان</h1>
  <p class="notice">اطلاعات مشتریان ثبت‌نام‌کرده و خلاصه سفارش‌های آن‌ها در این بخش نمایش داده می‌شود.</p>
  <div class="table-wrap"><table class="data-table">
    <thead><tr><th>نام و نام خانوادگی</th><th>ایمیل</th><th>شماره تماس</th><th>تعداد سفارش</th><th>مجموع خرید</th><th>تاریخ عضویت</th></tr></thead>
    <tbody>
    <?php foreach ($customers as $customer): ?>
      <tr><td><?= e($customer['full_name']) ?></td><td><?= e($customer['email']) ?></td><td><?= e($customer['phone'] ?: '—') ?></td><td><?= (int)$customer['order_count'] ?></td><td><?= toman($customer['total_spent']) ?></td><td><?= e(date('Y/m/d', strtotime($customer['created_at']))) ?></td></tr>
    <?php endforeach; ?>
    <?php if (!$customers): ?><tr><td colspan="6">هنوز مشتری‌ای ثبت‌نام نکرده است.</td></tr><?php endif; ?>
    </tbody>
  </table></div>
  <div class="center"><a class="btn btn-outline" href="index.php">بازگشت به پنل مدیریت</a></div>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
