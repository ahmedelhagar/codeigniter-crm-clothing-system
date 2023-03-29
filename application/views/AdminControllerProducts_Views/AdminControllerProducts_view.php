
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">بيانات المنتج</h4>
    <div class="container">
        <?php
            if(isset($process) && $process){
                echo $this->products_model->processAlert('success','alert','تم تعديل البيانات بنجاح.');
            }
        ?>
    </div>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/show" class="btn btn-success mx-3"><span class="fa fa-list"></span> كل المنتجات</a>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/create" class="btn btn-success mx-3"><span class="fa fa-plus"></span> أضف منتج</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php 
            if($product){
                $product = $product[0];
        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 float-right text-center">
            <img src="<?php echo 'https://kokykidswear.com/koky/public/uploads/'.$product->image; ?>" alt="<?php echo $product->name.' - '.$product->size.' - '.$product->color; ?> - لايتوفر اتصال بالانترنت لعرض الصورة">
            <h3 class="data-block-bg">رقم المنتج</h3>
            <h5><?php
            $explodedDate = explode('.',$product->created_at);
            $explodedDate1 = explode(':',$explodedDate[0]);
            echo $product->barcode.$explodedDate1[2].substr($explodedDate[1],0,2);
            ?></h5>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 float-right text-right">
            <p><?php echo $product->name.' - '.$product->size.' - '.$product->color; ?></p>
            <p>السعر : <?php echo $product->wholesale_price; ?> ج.م</p>
            <p>الكمية : <?php 
            
            foreach($this->products_model->getAllQ($product->created_at)['storages'] as $storage => $quantity){
                echo 'مخزن '.$storage.' به عدد '.$quantity.' قطعة<br>';
            }
            foreach($this->products_model->getAllQ($product->created_at)['stores'] as $store => $quantity){
                echo 'محل '.$store.' به عدد '.$quantity.' قطعة<br>';
            }
            
            ?></p>
            <p>
                <a href="<?php echo base_url(); ?>adminControllerProducts/edit/<?php echo $product->id; ?>"><span class="fa fa-cogs"></span> تعديل</a> |
                <a href="<?php echo base_url(); ?>adminControllerProducts/delete/<?php echo $product->id; ?>"><span class="fa fa-trash"></span> حذف</a>
            </p>
        </div>
        <?php
            }else{
        ?>
        <h5>لم نجد المنتج الذي تبحث عنه.</h5>
        <?php
            }
        ?>
    </div>
</div>
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">احصائيات المنتج</h4>
    <canvas id="canvas"></canvas>
</div>