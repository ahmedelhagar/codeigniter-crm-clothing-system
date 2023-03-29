
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المخازن</h4>
    <a href="<?php echo base_url(); ?>adminControllerStorages/create" class="btn btn-success mx-3"><span class="fa fa-user-plus"></span> أضف مخزن</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->storages_model->processAlert('success','alert','تم تعديل المخزن بنجاح.');
        }
    ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم المخزن</th>
                <th scope="col">اسم المخزن</th>
                <th scope="col">تعديل</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_storages){
                        //Loop All Users
                        foreach($all_storages as $storage){
                ?>
                    <tr>
                        <th scope="row"><?php echo $storage->id; ?></th>
                        <td><a href="<?php echo base_url(); ?>adminControllerStorages/products/<?php echo $storage->id; ?>"><?php echo $storage->name; ?></a></td>
                        <td><a href="<?php echo base_url(); ?>adminControllerStorages/edit/<?php echo $storage->id; ?>"><span class="fa fa-cogs"></span> تعديل</a></td>
                        <td><a href="<?php echo base_url(); ?>adminControllerStorages/delete/<?php echo $storage->id; ?>"><span class="fa fa-trash"></span> حذف</a></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد مخازن مسجلة.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>