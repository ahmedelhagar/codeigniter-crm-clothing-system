
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المكاتب</h4>
    <a href="<?php echo base_url(); ?>adminControllerOffices/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل المكاتب</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>adminControllerOffices/create" class="btn btn-success mx-3"><span class="fa fa-users-plus"></span> أضف مكتب</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($office) && $office){
                $officeData = $office[0];
                $submitValue = 'تعديل';
                $nextPage = 'update/'.$office[0]->id;
            }else{
                $officeData = '';
                $submitValue = 'أضف';
                $nextPage = 'write';
            }
            echo form_open_multipart('adminControllerOffices/'.$nextPage, $attributes);
            echo $this->offices_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->offices_model->processAlert('success','alert','تم انشاء المكتب بنجاح.');
            }
                //Create Form Fields
                $this->offices_model->formFields($fields,'officeForm',$officeData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>