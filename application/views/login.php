<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
    <link rel="shortcut icon" href="capture_N3V_icon.ico" type="image/x-icon">
  <title>شركة كوكي لملابس الأطفال</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- font aweasome -->
  <link href="<?php echo base_url(); ?>public/fontaweasome/css/all.css" rel="stylesheet">
  <!-- Style CSS -->
  <link href="<?php echo base_url(); ?>public/css/main.css" rel="stylesheet">
  <?php $this->load->view('holders/inPage_Styles'); ?>
</head>

<body>
    <div class="container">
        <div class="col-lg-6 col-md-6 col-sm-10 mx-auto mt-5 loginForm">
            <div class="container-fluid float-right">
                <a href="#">
                    <img src="<?php echo base_url(); ?>public/images/logo.png" alt="كوكي">
                </a>
            </div>
            <?php
            $attributes = array(
                'class' => 'adminForm text-right',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            echo form_open_multipart('cp/checkLogin', $attributes);
            echo $this->cp_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->cp_model->processAlert('danger','alert','يوجد خطأ ما برجاء المحاولة لاحقاً.');
            }
             //Create Form Fields
                $this->cp_model->formFields($fields,'loginForm','');
                //Submit The Form
                echo '<br />'.form_submit('add','دخول',array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
            ?>
        </div>
    </div>
</body>
</html>