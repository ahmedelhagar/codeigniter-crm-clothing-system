
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">التعاملات المالية</h4>
    <a href="<?php echo base_url(); ?>AdminControllerBalances/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل التعاملات المالية</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>AdminControllerBalances/create" class="btn btn-success mx-3"><span class="fa fa-users-plus"></span> أضف تعامل</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($balance) && $balance){
                $balanceData = $balance[0];
                $submitValue = 'تعديل';
                $nextPage = 'update/'.$balance[0]->id;
            }else{
                $balanceData = '';
                $submitValue = 'أضف';
                $nextPage = 'write';
            }
            echo form_open_multipart('AdminControllerBalances/'.$nextPage, $attributes);
            echo $this->balances_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->balances_model->processAlert('success','alert','تم انشاء التعامل بنجاح.');
            }
                //Create Form Fields
                $this->balances_model->formFields($fields,'balanceForm',$balanceData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>