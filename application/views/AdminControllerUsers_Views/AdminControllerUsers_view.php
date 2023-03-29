
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">بيانات العضو</h4>
    <div class="container">
        <?php
            if(isset($process) && $process){
                echo $this->users_model->processAlert('success','alert','تم تعديل البيانات بنجاح.');
            }
        ?>
    </div>
    <a href="<?php echo base_url(); ?>AdminControllerUsers/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل الأعضاء</a>
    <a href="<?php echo base_url(); ?>AdminControllerUsers/create" class="btn btn-success mx-3"><span class="fa fa-user-plus"></span> أضف عضو</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php 
            if($user){
                $user = $user[0];
        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 float-right text-center">
            <img src="https://kokykidswear.com/koky/<?php echo 'public/uploads/'.$user->image; ?>" alt="<?php echo $user->name; ?>">
            <h3 class="data-block-bg">رقم العضوية</h3>
            <h5><?php echo $user->id; ?></h5>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 float-right text-right">
            <p><?php echo $user->name; ?></p>
            <p>صلاحيات العضو : <?php echo $this->users_model->getPermissions($user->permissions); ?></p>
            <p>هاتف : <?php echo $user->mobile; ?></p>
            <p>
                <a href="<?php echo base_url(); ?>adminControllerUsers/edit/<?php echo $user->id; ?>"><span class="fa fa-cogs"></span> تعديل</a> |
                <a href="<?php echo base_url(); ?>adminControllerUsers/delete/<?php echo $user->id; ?>"><span class="fa fa-trash"></span> حذف</a>
            </p>
        </div>
        <?php
            }else{
        ?>
        <h5>لم نجد العضو الذي تبحث عنه.</h5>
        <?php
            }
        ?>
    </div>
</div>
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">احصائيات العضو</h4>
    <canvas id="canvas"></canvas>
</div>