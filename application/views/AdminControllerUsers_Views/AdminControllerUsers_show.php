
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">الأعضاء</h4>
    <a href="<?php echo base_url(); ?>AdminControllerUsers/create" class="btn btn-success mx-3"><span class="fa fa-user-plus"></span> أضف عضو</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم العضو</th>
                <th scope="col">الاسم</th>
                <th scope="col">الصلاحيات</th>
                <th scope="col">تعديل</th>
                <th scope="col">حذف</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_users){
                        //Loop All Users
                        foreach($all_users as $user){
                ?>
                    <tr>
                        <th scope="row"><?php echo $user->id; ?></th>
                        <td><a href="<?php echo base_url(); ?>adminControllerUsers/view/<?php echo $user->id; ?>"><?php echo $user->name; ?></a></td>
                        <td><?php echo $this->users_model->getPermissions($user->permissions); ?></td>
                        <td><a href="<?php echo base_url(); ?>adminControllerUsers/edit/<?php echo $user->id; ?>"><span class="fa fa-cogs"></span> تعديل</a></td>
                        <td><a href="<?php echo base_url(); ?>adminControllerUsers/delete/<?php echo $user->id; ?>"><span class="fa fa-trash"></span> حذف</a></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد أعضاء مسجلين.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>