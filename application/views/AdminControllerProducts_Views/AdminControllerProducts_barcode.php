
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">بيانات المنتج لطباعة الباركود</h4>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/show" class="btn btn-success mx-3"><span class="fa fa-list"></span> كل المنتجات</a>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/create" class="btn btn-success mx-3"><span class="fa fa-plus"></span> أضف منتج</a>
    <div class="r-barcode col-lg-12 col-md-12 col-sm-12 float-right text-right px-5 py-2">
            <input type="text" list="barcodes" autocomplete="off" id="barcode" name="barcode" value="" autofocus class="form-control" placeholder="الباركود">
            <datalist id="barcodes">
            <?php 
                if($products){foreach($products as $product){
            ?>
                <option id="list-<?php echo $product['id']; ?>" value="<?php echo $this->products_model->getpid($product['created_at'],$product['barcode']); ?>"><?php echo $product['name'].'-'.$product['color'].'-'.$product['size']; ?></option>
            <?php }} ?>
            </datalist>
            <form action="<?php echo base_url('AdminControllerProducts/print'); ?>" target="_blank" method="post">
                <div class="choosed-products">
                </div>
                <div class="col-12 float-right text-center mt-3"><button type="submit" class="btn btn-success"><span class="fa fa-print"></span> طباعة</button></div>
            </form>
    </div>
</div>