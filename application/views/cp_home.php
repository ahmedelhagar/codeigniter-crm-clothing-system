<div class="col-lg-12 col-md-12 col-sm-12 float-right "></div>
<div class="admin-block col-lg-4 col-md-4 col-sm-12 float-right text-right px-0 mb-3" style="background-color: #16a085;color:#fff;">
    <div class="container-fluid float-right text-center customers px-0">
        <h5 class="px-2 py-2" style="background-color: #1abc9c;">اجمالي الوارد</h5>
        <p class="mb-0" style="font-size: xx-large;"><span class="fa fa-money-check-alt"></span></p>
        <div class="container-fluid float-right text-center" style="font-size: x-large;">
            <?php 
                $allTransactions = $this->cp_model->getByData('transactions',' WHERE (state = 0)');
                $transactionAmount = 0;
                foreach($allTransactions as $transaction){
                    $transactionAmount += ($transaction['price']-$transaction['discount']);
                }
                echo $transactionAmount;
            ?>
            <span style="font-size: large;">ج.م</span>
        </div>
    </div>
</div>
<div class="admin-block col-lg-4 col-md-4 col-sm-12 float-right text-right px-0 mb-3" style="background-color: #2980b9;color:#fff;">
    <div class="container-fluid float-right text-center customers px-0">
        <h5 class="px-2 py-2" style="background-color: #3498db;">وارد اليوم</h5>
        <p class="mb-0" style="font-size: xx-large;"><span class="fa fa-money-check-alt"></span></p>
        <div class="container-fluid float-right text-center" style="font-size: x-large;">
            <?php 
                $now = new DateTime();
                $now->setTimezone(new DateTimezone('Africa/Cairo'));
                $dateNow = (array) $now;
                $date = explode(' ',$dateNow['date']);
                $allTransactions = $this->cp_model->getByData('transactions',' WHERE (state = 0) AND (created_at LIKE "%'.$date[0].'%")');
                $transactionAmount = 0;
                foreach($allTransactions as $transaction){
                    $transactionAmount += ($transaction['price']-$transaction['discount']);
                }
                echo $transactionAmount;
            ?>
            <span style="font-size: large;">ج.م</span>
        </div>
    </div>
</div>
<div class="admin-block col-lg-4 col-md-4 col-sm-12 float-right text-right px-0 mb-3" style="background-color: #c0392b;color:#fff;">
    <div class="container-fluid float-right text-center customers px-0">
        <h5 class="px-2 py-2" style="background-color: #e74c3c;">عدد فواتير اليوم السليمة</h5>
        <p class="mb-0" style="font-size: xx-large;"><span class="fa fa-users"></span></p>
        <div class="container-fluid float-right text-center" style="font-size: x-large;">
        <?php 
                $now = new DateTime();
                $now->setTimezone(new DateTimezone('Africa/Cairo'));
                $dateNow = (array) $now;
                $date = explode(' ',$dateNow['date']);
                $allTransactions = $this->cp_model->getByData('transactions',' WHERE (state = 0) AND (created_at LIKE "%'.$date[0].'%")');
                echo count($allTransactions);
            ?>
        </div>
    </div>
</div>
<div class="admin-block col-lg-4 col-md-4 col-sm-12 float-right text-right px-0 mb-3" style="background-color: #16a085;color:#fff;">
    <div class="container-fluid float-right text-center customers px-0">
        <h5 class="px-2 py-2" style="background-color: #1abc9c;">اجمالي المدفوع بالفيزا</h5>
        <p class="mb-0" style="font-size: xx-large;"><span class="fa fa-money-check-alt"></span></p>
        <div class="container-fluid float-right text-center" style="font-size: x-large;">
            <?php 
                $allTransactions = $this->cp_model->getByData('transactions',' WHERE (state = 0)');
                $transactionAmount = 0;
                foreach($allTransactions as $transaction){
                    if($transaction['byvisa']){
                        $transactionAmount += $transaction['byvisa'];
                    }elseif($transaction['method'] == 'visa'){
                        $transactionAmount += ($transaction['price']-$transaction['discount']);
                    }
                }
                echo $transactionAmount;
            ?>
            <span style="font-size: large;">ج.م</span>
        </div>
    </div>
</div>
<div class="admin-block col-lg-4 col-md-4 col-sm-12 float-right text-right px-0 mb-3" style="background-color: #2980b9;color:#fff;">
    <div class="container-fluid float-right text-center customers px-0">
        <h5 class="px-2 py-2" style="background-color: #3498db;">وارد الفيزا اليوم</h5>
        <p class="mb-0" style="font-size: xx-large;"><span class="fa fa-money-check-alt"></span></p>
        <div class="container-fluid float-right text-center" style="font-size: x-large;">
            <?php 
                $now = new DateTime();
                $now->setTimezone(new DateTimezone('Africa/Cairo'));
                $dateNow = (array) $now;
                $date = explode(' ',$dateNow['date']);
                $allTransactions = $this->cp_model->getByData('transactions',' WHERE (state = 0) AND (created_at LIKE "%'.$date[0].'%")');
                $transactionAmount = 0;
                foreach($allTransactions as $transaction){
                    if($transaction['byvisa']){
                        $transactionAmount += $transaction['byvisa'];
                    }elseif($transaction['method'] == 'visa'){
                        $transactionAmount += ($transaction['price']-$transaction['discount']);
                    }
                }
                echo $transactionAmount;
            ?>
            <span style="font-size: large;">ج.م</span>
        </div>
    </div>
</div>
<div class="admin-block col-lg-4 col-md-4 col-sm-12 float-right text-right px-0 mb-3" style="background-color: #c0392b;color:#fff;">
    <div class="container-fluid float-right text-center customers px-0">
        <h5 class="px-2 py-2" style="background-color: #e74c3c;">عدد فواتير اليوم المرتجعة</h5>
        <p class="mb-0" style="font-size: xx-large;"><span class="fa fa-users"></span></p>
        <div class="container-fluid float-right text-center" style="font-size: x-large;">
        <?php 
                $now = new DateTime();
                $now->setTimezone(new DateTimezone('Africa/Cairo'));
                $dateNow = (array) $now;
                $date = explode(' ',$dateNow['date']);
                $allTransactions = $this->cp_model->getByData('transactions',' WHERE (state = 1) AND (created_at LIKE "%'.$date[0].'%")');
                echo count($allTransactions);
            ?>
        </div>
    </div>
</div>

<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4">
    <h4 class="text-center">احصائيات الشركة</h4>
    <div class="admin-block col-lg-6 col-md-6 col-sm-12 float-right text-right">
    <h5 class="text-center">قيمة اجمالي الدخل</h5>
        <canvas id="canvas"></canvas>
    </div>
    <div class="admin-block col-lg-6 col-md-6 col-sm-12 float-right text-right">
        <div class="container-fluid float-right text-center customers" style="background-color: #2c3e50;">
            <h5 style="background-color: #34495e;">اجمالي عدد العملاء</h5>
            <p><span class="fa fa-user" style="color: #fff;"></span></p>
            <div class="container-fluid float-right text-center">
            <?php 
                $allClients = $this->cp_model->getByData('clients','');
                echo count($allClients);
            ?>
            </div>
        </div>
    </div>
</div>
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">اخر التعاملات</h4>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">تعامل رقم</th>
                <th scope="col">اسم العميل</th>
                <th scope="col">الهاتف</th>
                <th scope="col">الفرع</th>
                <th scope="col">البائع</th>
                <th scope="col">(ج.م)</th>
                <th scope="col">التفاصيل</th>
                <th scope="col">الحالة</th>
            </tr>
            </thead>
            <tbody>
            <?php if($transactions){foreach($transactions as $transaction){ ?>
                <tr>
                    <th scope="row"><?php echo str_replace(array(':','.','-',' '),array('','','',''),$transaction['created_at']).'<br />'; ?>
                        <a href="<?php echo base_url('AdminControllerCashier/transaction/'.$transaction['id']); ?>" class="btn btn-primary no-print" target="_blank" style="float: right;width: 100%;"><span class="fa fa-print"></span> طباعة</a>
                    </th>
                    <?php
                    if($transaction['c_id']){
                        $client = $this->cp_model->getByData('clients',' WHERE (created_at = \''.$transaction['c_id'].'\')');
                    }else{
                        $client = 0;
                    }
                        $place = $this->cp_model->getByData($transaction['place'].'s',' WHERE (id = '.$transaction['place_id'].')');
                        $user = $this->cp_model->getByData('users',' WHERE (id = '.$transaction['u_id'].')');
                    ?>
                    <td><?php echo ($client) ? $client[0]['name'] : 'مجهول'; ?></td>
                    <td><?php echo ($client) ? $client[0]['mobile'] : 'مجهول'; ?></td>
                    <td><?php echo ($place) ? $place[0]['name'] : 'مجهول'; ?></td>
                    <td><?php echo ($user) ? $user[0]['name'] : 'مجهول'; ?></td>
                    <td><?php echo $transaction['price']-$transaction['discount']; ?></td>
                    <td><?php
                    $transactionProducts = explode(',',$transaction['items']);
                    foreach($transactionProducts as $transactionProduct){
                        if($transactionProduct){
                            $transactionBy = explode('NxId',$transactionProduct);
                            $transactionProductData = $this->cp_model->getByData('products',' WHERE (created_at = \''.$this->cp_model->p_created_at($transactionBy[1]).'\')');
                            echo ($transactionProductData) ? 'عدد '.$transactionBy[0].' قطعة من '.$transactionProductData[0]['name'].'<br />' : 'عدد '.$transactionBy[0].' قطعة من منتج محذوف'.'<br />';
                        }
                    }
                    ?></td>
                    <td><?php echo (!$transaction['state']) ? 'مُباعة' : 'مُرتجعة'; ?></td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
    </div>
    <a href="<?php echo base_url('AdminControllerCashier/showAll'); ?>" class="view-all" style="width: 100%;
    float: right;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    border-top: 2px solid #f1f1f1;
    background: #2c3e50;
    color: #ffffff;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
    "><span class="fa fa-eye"></span> تقرير</a>
</div>