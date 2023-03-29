
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">الأعضاء</h4>
    <a href="<?php echo base_url(); ?>AdminControllerUsers/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل الأعضاء</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>AdminControllerUsers/create" class="btn btn-success mx-3"><span class="fa fa-users-plus"></span> أضف عضو</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($user) && $user){
                $userData = $user[0];
                $submitValue = 'تعديل';
                $nextPage = 'update/'.$user[0]->id;
            }else{
                $userData = '';
                $submitValue = 'أضف';
                $nextPage = 'write';
            }
            echo form_open_multipart('adminControllerUsers/'.$nextPage, $attributes);
            echo $this->users_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->users_model->processAlert('success','alert','تم انشاء العضو بنجاح.');
            }
                //Create Form Fields
                $this->users_model->formFields($fields,'userForm',$userData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>