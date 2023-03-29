
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المخازن</h4>
    <a href="<?php echo base_url(); ?>adminControllerStorages/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل المخازن</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>adminControllerStorages/create" class="btn btn-success mx-3"><span class="fa fa-users-plus"></span> أضف مخزن</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($storage) && $storage){
                $storageData = $storage[0];
                $submitValue = 'تعديل';
                $nextPage = 'update/'.$storage[0]->id;
            }else{
                $storageData = '';
                $submitValue = 'أضف';
                $nextPage = 'write';
            }
            echo form_open_multipart('adminControllerStorages/'.$nextPage, $attributes);
            echo $this->storages_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->storages_model->processAlert('success','alert','تم انشاء المخزن بنجاح.');
            }
                //Create Form Fields
                $this->storages_model->formFields($fields,'storageForm',$storageData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>