<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
<h4 class="text-center">البيع فرع <?php echo $store->name; ?></h4>
    <div class="r-barcode col-lg-12 col-md-12 col-sm-12 float-right text-right px-5 py-2">
            <input type="text" list="barcodes" autocomplete="off" id="barcode" name="barcode" value="" autofocus class="form-control" placeholder="الباركود">
            <datalist id="barcodes">
            <?php 
                $products = $this->cashier_model->getByData('products','');
                if($products){foreach($products as $product){
            ?>
                <option value="<?php echo $this->cashier_model->getpid($product['created_at'],$product['barcode']); ?>"><?php echo $product['name'].'-'.$product['color'].'-'.$product['size']; ?></option>
            <?php }} ?>
            </datalist>
    </div>
    <div class="receipts col-lg-4 col-md-4 col-sm-12 float-right text-right mt-0 px-2 py-2">
        <div class="btn btn-default r-def receipt" num-data="1" id="receipt">
            <span class="fa fa-receipt"></span>
            1
        </div>
        <div class="btn btn-success r-add-new">
            <span class="fa fa-plus"></span>
        </div>
        <!--<div class="btn btn-default" id="receipt">
            <span class="fa fa-receipt"></span>
            2
        </div>
        <div class="btn btn-primary" id="receipt">
            <span class="fa fa-receipt"></span>
            3
        </div>
        <div class="btn btn-primary" id="receipt">
            <span class="fa fa-receipt"></span>
            4
        </div>
        <div class="btn btn-primary" id="receipt">
            <span class="fa fa-receipt"></span>
            5
        </div>-->
        <div class="product-items col-lg-12 col-md-12 col-sm-12 float-right text-right px-2 py-2">
            <div class="col-lg-12 col-md-12 col-sm-12 float-right px-2 py-2">
                <h5 class="text-right float-right">
                    فاتورة<!-- #<span class="receipt-num">1</span>-->
                </h5>
                <!--<div class="btn btn-danger float-left" id="delete-re">
                    <span class="fa fa-trash"></span>
                </div>-->
            </div>
            <div class="all-items"></div>
            <h5 id="empty-barcodes">برجاء مسح باركود <span class="text-left float-left fa fa-barcode"></span></h5>
            <h5>الاجمالي <span class="text-left float-left"><span id="price">0</span> ج.م</span></h5>
            <h5>الخصم <span class="text-left float-left">ج.م</span></h5>
            <div class="col-lg-12 col-md-12 col-sm-12 float-right px-2 py-2">
                <input class="form-control" id="discount" min="0" type="number">
            </div>
            <h5>المطلوب <span class="text-left float-left"><span id="totalPrice">0</span> ج.م</span></h5>
            <div class="col-lg-4 col-md-4 col-sm-12 float-right px-2 py-2 btn btn-success" data-toggle="modal" id="cashDialogBtn" data-target="#cashDialog">
                <p><span class="fa fa-money-bill-wave"></span></p>
                كاش
            </div>
            <!-- Modal -->
            <div class="modal fade" id="cashDialog" tabindex="-1" role="dialog" aria-labelledby="cashDialogLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">الدفع كاش</h5>
                </div>
                <div class="modal-body">
                    <h4>المطلوب <span class="text-left float-left"><span id="totalPriceDialog">0</span>ج.م</span></h4>
                    <div class="case">
                        <input type="text" id="paid" placeholder="المبلغ المدفوع" class="form-control">
                        <h4>الباقي <span class="text-left float-left"><span id="rest">0</span>ج.م</span></h4>
                    </div>
                    <div class="byVisa">
                        <input type="text" id="paid-visa" placeholder="المبلغ المدفوع بالفيزا" class="form-control">
                    </div>
                    <input type="text" id="client_mobile" placeholder="رقم هاتف العميل" class="form-control">
                    <div id="register_client"></div>
                    <!--<div class="btn btn-success mt-3">
                        <span class="fa fa-plus"></span>
                        أضف العميل
                    </div>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">إلغاء</button>
                    <button type="button" id="pay-cash" class="btn btn-success">تحصيل</button>
                </div>
                </div>
            </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 float-right px-2 py-2 btn btn-primary" id="visaDialogBtn">
                <p><span class="fa fa-credit-card"></span></p>
                فيزا
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 float-right px-2 py-2 btn btn-warning" id="mergedDialogBtn">
                <p><span class="fa fa-credit-card"></span> + <span class="fa fa-money-bill-wave"></span></p>
                مشترك
            </div>
        </div>
    </div>
    <h4 class="text-center">العمليات السابقة</h4>
    <div class="col-lg-8 col-md-8 col-sm-12 float-right text-right mt-0 px-2 py-2">
    </div>
    <div class="receipts past-re col-lg-8 col-md-8 col-sm-12 float-right text-right mt-0 px-2 py-2">
        <?php foreach($transactions as $transaction){ ?>
        <div class="product-items col-lg-12 col-md-12 col-sm-12 float-right text-right mb-3 px-2 py-2">
            <div class="col-lg-12 col-md-12 col-sm-12 float-right px-2 py-2">
                <h5 class="text-right float-right mb-0">
                    العملية لـ
                </h5><br />
                <div class="text-muted">
                    <div class="cust">
                        <?php 
                        if($transaction['c_id']){
                        ?>
                        العميل/ <?php 
                        $client = $this->cashier_model->getByData('clients',' WHERE (created_at = \''.$transaction['c_id'].'\')');
                        echo $client[0]['name'].' | ';
                        }
                        ?>
                        تم الدفع <?php echo $transaction['method']; ?>
                    </div>
                    <?php $state=(int) $transaction['state'];if($state < 1){ ?>
                    <a target="_blank" class="btn btn-danger float-left" id="delete-re-<?php echo $transaction['id']; ?>" onclick="return refund(<?php echo $transaction['id']; ?>);" style="color:#fff;">
                        مرتجع
                        <span class="fa fa-reply"></span>
                    </a>
                    <?php }else{ ?>
                        <br/><code>مرتجعة</code>
                    <?php } ?>
                    <a href="<?php echo base_url('AdminControllerCashier/transaction/'.$transaction['id']); ?>" target="_blank" class="btn btn-success float-left" id="print-re">
                        طباعة
                        <span class="fa fa-print"></span>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>