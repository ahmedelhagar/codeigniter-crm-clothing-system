
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0 col-print-12">
    <h4 class="text-center">التعاملات</h4>
    <a href="<?php echo base_url(); ?>AdminControllerBalances/create" class="btn btn-success mx-3 no-print"><span class="fa fa-user-plus"></span> أضف تعامل</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->balances_model->processAlert('success','alert','تم تعديل التعامل بنجاح.');
        }
    ?>
        <form method="post" action="<?php echo base_url('AdminControllerBalances/show'); ?>">
            <label for="from" class="float-right">من</label>
                <input class="form-control float-right" id="from" value="<?php echo ($from) ? $from : '' ; ?>" placeholder="من" type="date" id="from" name="from">
            <label for="to" class="float-right">إلى</label>
                <input class="form-control float-right" id="to" value="<?php echo ($to) ? $to : '' ; ?>" placeholder="إلى" type="date" id="to" name="to">
            <label for="store" class="float-right">الفرع</label>
                <select name="store" id="store" class="form-control">
                    <?php
                        $stores = $this->balances_model->getByData('stores','');
                        if($stores){
                            foreach($stores as $store){
                        ?>
                            <option <?php echo ($store_id == $store['id']) ? 'selected': ''; ?> value="<?php echo $store['id']; ?>"><?php echo $store['name']; ?></option>
                        <?php
                        }}
                    ?>
                </select>
                <div class="btn btn-primary no-print mt-2" onclick="window.print()"><span class="fa fa-print"></span> طباعة التقرير</div>
                <button class="btn btn-success no-print mt-2" type="submit"><span class="fa fa-search"></span> تصفية</button>
        </form>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم التعامل</th>
                <th scope="col">سبب التعامل</th>
                <th scope="col">مبلغ التعامل</th>
                <th scope="col">الحالة التعامل</th>
                <th scope="col">لحظة التعامل</th>
                <th scope="col">الفرع</th>
                <th scope="col">الموظف</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_balances){
                        //Loop All Users
                        foreach($all_balances as $balance){
                        $place = $this->balances_model->getByData($balance->place.'s',' WHERE (id = '.$balance->place_id.')');
                ?>
                    <tr>
                        <th scope="row"><?php echo $balance->id; ?></th>
                        <td><?php echo $balance->reason; ?></td>
                        <td><?php echo $balance->amount; ?></td>
                        <td><?php echo ($balance->state == 'add') ? 'ايداع' : 'سحب'; ?></td>
                        <td><?php echo explode('.',$balance->created_at)[0]; ?></td>
                        <td><?php echo ($place) ? $place[0]['name'] : 'مجهول'; ?></td>
                        <td><?php 
                            $user = $this->balances_model->getByData('users',' WHERE (id = '.$balance->u_id.')');
                            if($user){
                                echo $user[0]['name'];
                            }
                        ?></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد تعاملات مسجلة.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>