<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
<h4 class="text-center">عمليات الشراء</h4>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <div class="col-12 float-right py-2">
            <form method="post" action="<?php echo base_url('AdminControllerCashier/showAll'); ?>">
            <label for="from" class="float-right">من</label>
                <input class="form-control float-right" id="from" value="<?php echo ($from) ? $from : '' ; ?>" placeholder="من" type="date" id="from" name="from">
            <label for="to" class="float-right">إلى</label>
                <input class="form-control float-right" id="to" value="<?php echo ($to) ? $to : '' ; ?>" placeholder="إلى" type="date" id="to" name="to">
            <label for="store" class="float-right">الفرع</label>
            <select name="store" id="store" class="form-control">
                <?php
                    $stores = $this->cashier_model->getByData('stores','');
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
        </div>
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
            <?php $total = 0;$byvisa = 0;if($transactions){foreach($transactions as $transaction){
                $total += $transaction['price']-$transaction['discount'];
                if($transaction['method'] == 'visa'){
                    $byvisa += $transaction['price']-$transaction['discount'];
                }else{
                    $byvisa += (int) $transaction['byvisa'];
                }
                ?>
                <tr>
                    <th scope="row"><?php echo str_replace(array(':','.','-',' '),array('','','',''),$transaction['created_at']).'<br />'; ?>
                        <a href="<?php echo base_url('AdminControllerCashier/transaction/'.$transaction['id']); ?>" class="btn btn-primary no-print" target="_blank" style="float: right;width: 100%;"><span class="fa fa-print"></span> طباعة</a>
                    </th>
                    <?php
                    if($transaction['c_id']){
                        $client = $this->cashier_model->getByData('clients',' WHERE (created_at = \''.$transaction['c_id'].'\')');
                    }else{
                        $client = 0;
                    }
                        $place = $this->cashier_model->getByData($transaction['place'].'s',' WHERE (id = '.$transaction['place_id'].')');
                        $user = $this->cashier_model->getByData('users',' WHERE (id = '.$transaction['u_id'].')');
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
                            $transactionProductData = $this->cashier_model->getByData('products',' WHERE (created_at = \''.$this->cashier_model->p_created_at($transactionBy[1]).'\')');
                            echo ($transactionProductData) ? 'عدد '.$transactionBy[0].' قطعة من '.$transactionProductData[0]['name'].' - '.$transactionProductData[0]['size'].' - '.$transactionProductData[0]['color'].'<br />' : 'عدد '.$transactionBy[0].' قطعة من منتج محذوف'.'<br />';
                        }
                    }
                    ?></td>
                    <td><?php echo (!$transaction['state']) ? 'مُباعة' : 'مُرتجعة'; ?></td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>
        <h4>الملخص:</h4>
        <h3>الاجمالي: <?php echo $total; ?> و الخزينة: <?php echo $total-$byvisa; ?> و الفيزا: <?php echo $byvisa; ?></h3>
    </div>
</div>