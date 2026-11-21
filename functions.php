<?php
function e($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function toman($n){ return number_format((int)$n) . ' تومان'; }
function logged_in(){ return !empty($_SESSION['user']); }
function is_admin(){ return logged_in() && (($_SESSION['user']['role'] ?? '') === 'admin'); }
function require_login(){ if(!logged_in()){ header('Location: login.php?next='.urlencode($_SERVER['REQUEST_URI'])); exit; } }
function csrf_token(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(){ if($_SERVER['REQUEST_METHOD']==='POST' && !hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')){ http_response_code(419); exit('درخواست نامعتبر است. صفحه را تازه‌سازی کنید.'); } }
function flash($key,$value=null){ if($value!==null){$_SESSION['flash'][$key]=$value;return;} $v=$_SESSION['flash'][$key]??''; unset($_SESSION['flash'][$key]); return $v; }
