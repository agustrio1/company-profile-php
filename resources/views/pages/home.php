<?php
// File: resources/views/pages/home.php
$isHtmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';

if (!$isHtmx) {
    $title = $company->name ?? 'Beranda';
    ob_start();
}
?>

<?php require view_path('partials/home/hero.php'); ?>

<?php require view_path('partials/home/services.php'); ?>

<?php require view_path('partials/home/why-choose-us.php'); ?>

<?php require view_path('partials/home/team.php'); ?>

<?php require view_path('partials/home/testimonials.php'); ?>

<?php require view_path('partials/home/blog.php'); ?>

<?php require view_path('partials/home/cta.php'); ?>

<?php
if (!$isHtmx) {
    $content = ob_get_clean();
    require view_path('layouts/app.php');
}
?>