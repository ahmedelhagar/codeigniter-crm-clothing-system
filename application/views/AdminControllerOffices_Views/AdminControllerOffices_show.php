
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المكاتب</h4>
    <a href="<?php echo base_url(); ?>AdminControllerOffices/create" class="btn btn-success mx-3"><span class="fa fa-user-plus"></span> أضف مكتب</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->offices_model->processAlert('success','alert','تم تعديل المكتب بنجاح.');
        }
    ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم المكتب</th>
                <th scope="col">اسم المكتب</th>
                <th scope="col">تعديل</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_offices){
                        //Loop All Users
                        foreach($all_offices as $office){
                ?>
                    <tr>
                        <th scope="row"><?php echo $office->id; ?></th>
                        <td><a href="<?php echo base_url(); ?>AdminControllerOffices/products/<?php echo $office->id; ?>"><?php echo $office->name; ?></a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerOffices/edit/<?php echo $office->id; ?>"><span class="fa fa-cogs"></span> تعديل</a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerOffices/delete/<?php echo $office->id; ?>"><span class="fa fa-trash"></span> حذف</a></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد مكاتب مسجلة.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>