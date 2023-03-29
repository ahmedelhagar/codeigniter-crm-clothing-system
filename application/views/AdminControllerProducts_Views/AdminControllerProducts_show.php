
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المنتجات</h4>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/create" class="btn btn-success mx-3"><span class="fa fa-plus"></span> أضف منتج</a>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/barcode" class="btn btn-primary mx-3"><span class="fa fa-barcode"></span> طباعة باركود</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->products_model->processAlert('success','alert','تم تعديل المنتج بنجاح.');
        }
    ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم المنتج</th>
                <th scope="col">اسم المنتج</th>
                <th scope="col">نقل</th>
                <th scope="col">أضف لون/مقاس</th>
                <th scope="col">تعديل</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_products){
                        //Loop All Users
                        foreach($all_products as $product){
                ?>
                    <tr>
                        <th scope="row"><?php
                        echo $this->products_model->getpid($product->created_at,$product->barcode);
                        ?></th>
                        <td><a href="<?php echo base_url(); ?>AdminControllerProducts/view/<?php echo $product->id; ?>"><?php echo $product->name.' - '.$product->size.' - '.$product->color; ?></a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerProducts/addstock/<?php echo $product->id; ?>"><span class="fa fa-boxes"></span> نقل كمية</a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerProducts/addmore/<?php echo $product->id; ?>"><span class="fa fa-plus"></span> أضف لون/مقاس</a></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerProducts/edit/<?php echo $product->id; ?>"><span class="fa fa-cogs"></span> تعديل</a></td>
                    </tr>
                <?php
                        }
                    }else{
                ?>
                <h5>لايوجد منتجات مسجلة.</h5>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>