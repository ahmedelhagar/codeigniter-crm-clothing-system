
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">الرسائل</h4>
    <h5 class="text-center">رسالة جديدة</h5>
    <form action="<?php echo base_url('api/insertMessage'); ?>" class="col-12 float-right px-2 py-2" method="post">
        <label for="message">محتوى الرسالة</label>
        <textarea name="message" class="form-control" id="message"></textarea><br />
        <label for="usersSelect">إلى</label>
        <select name="users" class="form-control" id="usersSelect">
        <?php 
            $users = $this->users_model->getByData('users','');
            if($users){
                foreach($users as $user){
        ?>
            <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
        <?php }} ?>
        </select><br />
        <button class="btn btn-success mx-3"><span class="fa fa-envelope"></span> أرسل</button>
    </form>
    <div class="container-fluid float-right px-3 pt-3 pb-0" id="allMessages"></div>
</div>