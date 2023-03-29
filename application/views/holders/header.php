<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
    <link rel="shortcut icon" href="capture_N3V_icon.ico" type="image/x-icon">
  <title>شركة كوكي لملابس الأطفال</title>
  
  <script src="<?php echo base_url(); ?>public/js/jquery.min.js"></script>
  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet">
  <!-- font aweasome -->
  <link href="<?php echo base_url(); ?>public/fontaweasome/css/all.css" rel="stylesheet">
  <!-- Style CSS -->
  <link href="<?php echo base_url(); ?>public/css/main.css" rel="stylesheet">
  <?php $this->load->view('holders/inPage_Styles'); ?>
</head>

<body>
<div class="container-fluid float-right">
<div class="logo-print">
    <img src="<?php echo base_url(); ?>public/images/logo.png" alt="كوكي">
</div>
<div class="menu-toggle"><span class="fa fa-bars"></span></div>
        <div class="col-lg-3 col-md-3 col-sm-12 admin-menu float-right text-right no-print">
            <div class="logo">
                <a href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url(); ?>public/images/logo.png" alt="كوكي">
                </a>
            </div>
            <ul>
                <li><a href="<?php echo base_url(); ?>" class="active"><span class="fa fa-home"></span> الرئيسية</a></li>
                <?php if($this->perms_model->getPerms('products',1)){ ?>
                    <li><a href="<?php echo base_url(); ?>adminControllerProducts/show"><span class="fa fa-sitemap"></span> المنتجات</a></li>
                <?php } ?>
                <?php if($this->perms_model->getPerms('cashierReports',1)){ ?>
                <li><a href="<?php echo base_url(); ?>adminControllerCashier/showAll"><span class="fa fa-shopping-cart"></span> عمليات الشراء</a></li>
                <?php } ?>
                <?php if($this->perms_model->getPerms('balance',1)){ ?>
                <li><a href="<?php echo base_url(); ?>adminControllerBalances/show"><span class="fa fa-money-check-alt"></span> الأرصدة</a></li>
                <?php } ?>
                <?php if($this->perms_model->getPerms('users',1)){ ?>
                <li><a href="<?php echo base_url(); ?>adminControllerUsers/show"><span class="fa fa-users"></span> الأعضاء (الموظفين)</a></li>
                <?php } ?>
                <?php if($this->perms_model->getPerms('stores',1)){ ?>
                <li><a href="<?php echo base_url(); ?>adminControllerStores/show"><span class="fa fa-store"></span> الفروع</a></li>
                <?php } ?>
                <?php if($this->perms_model->getPerms('storages',1)){ ?>
                <li><a href="<?php echo base_url(); ?>adminControllerStorages/show"><span class="fa fa-boxes"></span> المخازن</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 admin-con float-left text-right col-print-12">
            <div class="container">
                <a class="float-right btn btn-danger no-print" href="<?php echo base_url('cp/logout'); ?>">خروج</a>
                <div class="welcome-user col-lg-9 col-md-9 col-sm-12 float-right text-right no-print">
                    <img src="https://kokykidswear.com/koky/public/uploads/<?php echo $this->session->userdata('image'); ?>" alt="<?php echo $this->session->userdata('name'); ?>">
                    <span class="welcome-msg">مرحباً , <?php echo $this->session->userdata('name'); ?></span>
                    
                        <?php
                        $connectionTest = $this->perms_model->is_connected();
                        if($connectionTest){
                        ?>
                        <a class="no-print" id="notfToggle" href="#"><span class="fa fa-bell"></span><div class="notfications" id="notfs-num"></div></a>
                        <?php
                        }else{
                        ?>
                        <div class="notfications no-print">انت غير متصل بالانترنت</div>
                        <?php } ?>
                        <div class="notfData">
                            <ul id="messagesData"></ul>
                            <a href="<?php echo base_url('adminControllerUsers/messages'); ?>" class="btn btn-success" style="width:100%;float:right;"><span class="fa fa-comments"></span> الرسائل</a>
                        </div>
                </div>
                <a href="<?php echo base_url(); ?>adminControllerCashier/" class="btn btn-warning btn-lg mt-3 float-left no-print"><span class="fa fa-store"></span> نظام الكاشير</a>
                <div class="container-fluid float-right">
                <div onclick="return sync();" class="btn btn-success btn-lg mt-3 float-left no-print"><span class="fa fa-sync"></span> تحديث الجداول</div>
                <div onclick="return update();" class="btn btn-warning btn-lg mt-3 float-left no-print"><span class="fa fa-sync"></span> تحديث النظام</div>
                <div class="sync">
                    <h3>جار التحديث لاتغلق المتصفح ...</h3>
                    <p class="text-muted">سيتم اغلاق الصفحة تلقائياً عند الانتهاء</p>
                    <br><br><br>
                    <div class="stats">
                        بانتظار رد السيرفر...
                    </div>
                    <img src="<?php echo base_url('public/images/loader.gif'); ?>" alt="جار التحديث">
                </div>