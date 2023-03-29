
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المصانع</h4>
    <a href="<?php echo base_url(); ?>AdminControllerFactories/create" class="btn btn-success mx-3"><span class="fa fa-user-plus"></span> أضف مصنع</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->factories_model->processAlert('success','alert','تم تعديل المصنع بنجاح.');
        }
    ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم المصنع</th>
                <th scope="col">اسم المصنع</th>
                <th scope="col">تعديل</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_factories){
                        //Loop All Users
                        foreach($all_factories as $factory){
                ?>
                    <tr>
                        <th scope="row"><?php echo $factory->id; ?></th>
                        <td><a href="<?php echo base_url(); ?>AdminControllerFactories/products/<?php echo $factory->id; ?>"><?php echo $factory->name; ?></a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerFactories/edit/<?php echo $factory->id; ?>"><span class="fa fa-cogs"></span> تعديل</a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerFactories/delete/<?php echo $factory->id; ?>"><span class="fa fa-trash"></span> حذف</a></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد مصانع مسجلة.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>