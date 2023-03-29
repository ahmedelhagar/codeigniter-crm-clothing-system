
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المصانع</h4>
    <a href="<?php echo base_url(); ?>adminControllerFactories/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل المصانع</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>adminControllerFactories/create" class="btn btn-success mx-3"><span class="fa fa-users-plus"></span> أضف مصنع</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($factory) && $factory){
                $factoryData = $factory[0];
                $submitValue = 'تعديل';
                $nextPage = 'update/'.$factory[0]->id;
            }else{
                $factoryData = '';
                $submitValue = 'أضف';
                $nextPage = 'write';
            }
            echo form_open_multipart('adminControllerFactories/'.$nextPage, $attributes);
            echo $this->factories_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->factories_model->processAlert('success','alert','تم انشاء المصنع بنجاح.');
            }
                //Create Form Fields
                $this->factories_model->formFields($fields,'factoryForm',$factoryData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>