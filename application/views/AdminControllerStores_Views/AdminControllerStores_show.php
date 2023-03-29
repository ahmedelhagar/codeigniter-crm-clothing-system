
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">الفروع</h4>
    <a href="<?php echo base_url(); ?>AdminControllerStores/create" class="btn btn-success mx-3"><span class="fa fa-user-plus"></span> أضف فرع</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->stores_model->processAlert('success','alert','تم تعديل الفرع بنجاح.');
        }
    ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم الفرع</th>
                <th scope="col">اسم الفرع</th>
                <th scope="col">تعديل</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_stores){
                        //Loop All Users
                        foreach($all_stores as $store){
                ?>
                    <tr>
                        <th scope="row"><?php echo $store->id; ?></th>
                        <td><a href="<?php echo base_url(); ?>AdminControllerStores/products/<?php echo $store->id; ?>"><?php echo $store->name; ?></a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerStores/edit/<?php echo $store->id; ?>"><span class="fa fa-cogs"></span> تعديل</a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerStores/delete/<?php echo $store->id; ?>"><span class="fa fa-trash"></span> حذف</a></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد فروع مسجلة.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>