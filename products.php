<?php
require_once __DIR__ . '/../includes/bootstrap.php';
if (!is_admin()) { header('Location: ../login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? 'save';
    if ($action === 'deactivate') {
        $pdo->prepare("UPDATE products SET is_active=0 WHERE id=?")->execute([(int)$_POST['id']]);
        header('Location: products.php'); exit;
    }
    $data = [trim($_POST['name']), trim($_POST['category']), trim($_POST['description']), max(0,(int)$_POST['price']), max(0,(int)$_POST['stock']), trim($_POST['image']), (int)isset($_POST['featured'])];
    if (!empty($_POST['id'])) {
        $pdo->prepare("UPDATE products SET name=?,category=?,description=?,price=?,stock=?,image=?,is_featured=? WHERE id=?")->execute([...$data,(int)$_POST['id']]);
    } else {
        $pdo->prepare("INSERT INTO products(name,category,description,price,stock,image,rating,is_featured,is_active) VALUES(?,?,?,?,?,?,5,?,1)")->execute($data);
    }
    header('Location: products.php'); exit;
}
$editing = null;
if (isset($_GET['edit'])) { $st=$pdo->prepare("SELECT * FROM products WHERE id=?"); $st->execute([(int)$_GET['edit']]); $editing=$st->fetch(); }
$products=$pdo->query("SELECT * FROM products ORDER BY is_active DESC,id DESC")->fetchAll();
$categories=['cleansing'=>'پاک‌سازی','toner'=>'تونر و میست','hydration'=>'آبرسانی و مرطوب‌کننده','brightening'=>'ضدلک و روشن‌کننده','antiaging'=>'ضدچروک و جوان‌سازی','special'=>'مراقبت تخصصی','acne'=>'ضدجوش و کنترل چربی','sensitive'=>'پوست حساس و آرام‌بخش','protection'=>'محافظت و ضدآفتاب','perfume'=>'عطر و ادکلن','home-fragrance'=>'خوشبوکننده‌ها'];
$pageTitle='مدیریت محصولات | پنل رُزآرا'; include __DIR__.'/../includes/header.php';
?>
<main class="page-content container"><h1 class="page-title">مدیریت محصولات</h1>
<form class="form-wrap" method="post"><h2><?= $editing ? 'ویرایش محصول' : 'افزودن محصول جدید' ?></h2><input type="hidden" name="csrf" value="<?=csrf_token()?>"><input type="hidden" name="id" value="<?= (int)($editing['id']??0) ?>">
<div class="field"><label>نام محصول</label><input name="name" required value="<?=e($editing['name']??'')?>"></div>
<div class="field"><label>توضیحات</label><textarea name="description" required><?=e($editing['description']??'')?></textarea></div>
<div class="field"><label>قیمت به تومان</label><input type="number" min="0" name="price" required value="<?=e($editing['price']??'')?>"></div>
<div class="field"><label>موجودی</label><input type="number" min="0" name="stock" required value="<?=e($editing['stock']??'')?>"></div>
<div class="field"><label>مسیر تصویر (مثلاً img/product-serum.jpg)</label><input name="image" required value="<?=e($editing['image']??'')?>"></div>
<div class="field"><label>دسته‌بندی</label><select name="category"><?php foreach($categories as $key=>$label):?><option value="<?=e($key)?>" <?=($editing['category']??'')===$key?'selected':''?>><?=e($label)?></option><?php endforeach;?></select></div>
<label><input type="checkbox" name="featured" <?=!empty($editing['is_featured'])?'checked':''?>> محصول منتخب</label><p><button class="primary-btn">ذخیره محصول</button> <?php if($editing):?><a class="btn btn-outline" href="products.php">لغو ویرایش</a><?php endif;?></p></form>
<div class="table-wrap"><table class="data-table"><thead><tr><th>نام محصول</th><th>دسته</th><th>قیمت</th><th>موجودی</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
<?php foreach($products as $product):?><tr><td><?=e($product['name'])?></td><td><?=e($categories[$product['category']]??$product['category'])?></td><td><?=toman($product['price'])?></td><td><?=(int)$product['stock']?></td><td><?=$product['is_active']?'فعال':'غیرفعال'?></td><td><a href="?edit=<?=(int)$product['id']?>">ویرایش</a> <?php if($product['is_active']):?><form method="post" style="display:inline" onsubmit="return confirm('این محصول از فروشگاه پنهان شود؟')"><input type="hidden" name="csrf" value="<?=csrf_token()?>"><input type="hidden" name="action" value="deactivate"><input type="hidden" name="id" value="<?=(int)$product['id']?>"><button type="submit">غیرفعال‌سازی</button></form><?php endif;?></td></tr><?php endforeach;?>
<?php if(!$products):?><tr><td colspan="6">محصولی ثبت نشده است.</td></tr><?php endif;?></tbody></table></div></main>
<?php include __DIR__.'/../includes/footer.php'; ?>
