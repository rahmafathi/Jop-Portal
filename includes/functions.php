<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. دوال الأمان والتنظيف (Security & Sanitization)

// تنظيف المدخلات من السكريبتات والمسافات الزائدة
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// التوجيه لصفحة أخرى
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

// 2. دوال الحماية والصلاحيات (Auth Protection)

// حماية صفحات الأعضاء  (profile.php)
function checkLogin()
{
    if (!isset($_SESSION['user_id'])) {
        redirect("/Jop-Portal/login.php");
    }
}

function checkAdmin()
{
    checkLogin();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        redirect("/Jop-Portal/index.php");
    }
}

// ----------------------------------------------------
// 3. دوال رسائل التنبيه (Alert Messages)
// ----------------------------------------------------

function setMessage($type, $msg)
{
    $_SESSION['message'] = [
        'type' => $type, // success, danger, warning, info
        'text' => $msg
    ];
}

function displayMessage()
{
    if (isset($_SESSION['message'])) {
        // التحقق مما إذا كانت الرسالة مصفوفة أم نص عادي لمنع أي خطأ مستقبلي
        if (is_array($_SESSION['message'])) {
            $type = $_SESSION['message']['type'];
            $text = $_SESSION['message']['text'];
        } else {
            $type = 'success';
            $text = $_SESSION['message'];
        }

        // تحويل نوع success أو danger لتتوافق مع الـ Dark Theme المخصص
        echo '
        <div class="alert alert-' . $type . ' alert-dismissible fade show text-center shadow-sm rounded-4 my-3" role="alert" style="background: rgba(30, 41, 59, 0.95); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff;">
            ' . htmlspecialchars($text) . '
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
        
        unset($_SESSION['message']);
    }
}

// 4. دوال التحقق من صحة البيانات (Validation Functions)

// التأكد إن النص مش فاضي
function checkEmpty($value)
{
    return empty(trim($value));
}

// التحقق من صحة صيغة الإيميل
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// التحقق من طول الباسورد (مثلاً 6 حروف على الأقل)
function validatePassword($password, $minLen = 6)
{
    return strlen($password) >= $minLen;
}

// التحقق من رقم التليفون (أرقام فقط ومكون من 11 رقم)
function validatePhone($phone)
{
    return preg_match('/^01[0125][0-9]{8}$/', $phone);
}

// التحقق من أن المدخل رقم موجب (للأسعار والأعمار)
function validateNumber($number)
{
    return is_numeric($number) && $number > 0;
}