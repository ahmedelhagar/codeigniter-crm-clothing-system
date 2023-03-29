
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">الفروع</h4>
    <a href="<?php echo base_url(); ?>adminControllerStores/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل الفروع</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>adminControllerStores/create" class="btn btn-success mx-3"><span class="fa fa-users-plus"></span> أضف فرع</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($store) && $store){
                $storeData = $store[0];
                $submitValue = 'تعديل';
                $nextPage = 'update/'.$store[0]->id;
            }else{
                $storeData = '';
                $submitValue = 'أضف';
                $nextPage = 'write';
            }
            echo form_open_multipart('adminControllerStores/'.$nextPage, $attributes);
            echo $this->stores_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->stores_model->processAlert('success','alert','تم انشاء الفرع بنجاح.');
            }
                //Create Form Fields
                $this->stores_model->formFields($fields,'storeForm',$storeData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>