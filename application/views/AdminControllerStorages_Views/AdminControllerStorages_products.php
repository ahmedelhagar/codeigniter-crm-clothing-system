
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المنتجات المرسلة لـ مخزن <?php echo $storage[0]['name']; ?></h4>
    <a href="<?php echo base_url(); ?>AdminControllerStorages/show" class="btn btn-success mx-3"><span class="fa fa-list"></span> كل المخازن</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
    <?php
        if(isset($process) && $process){
            echo $this->storages_model->processAlert('success','alert','تم تعديل المنتج بنجاح.');
        }
    ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم المنتج</th>
                <th scope="col">اسم المنتج</th>
                <th scope="col">الكمية المرسلة</th>
                <th scope="col">مرسلة مع</th>
                <th scope="col">قبول</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    if($all_moved_products){
                        //Loop All Users
                        foreach($all_moved_products as $moved_product){
                            $product = $this->storages_model->getByData('products',' WHERE (created_at = \''.$moved_product->p_created_at.'\')');
                            if($product){
                                $product = $product[0];
                ?>
                    <tr>
                        <th scope="row"><?php
                        echo $this->cashier_model->getpid($product['created_at'],$product['barcode']);
                        ?></th>
                        <td><a href="<?php echo base_url(); ?>AdminControllerProducts/view/<?php echo $product['id']; ?>"><?php echo $product['name'].' - '.$product['color'].' - '.$product['size']; ?></a></td>
                        <td><b><?php echo $moved_product->quantity; ?></b></td>
                        <td><b><?php 
                            $user = $this->storages_model->getByData('users',' WHERE (id = '.$moved_product->with_u_id.')');
                            if($user){
                                $user = $user[0];
                            }
                            echo $user['name'];
                        ?></b></td>
                        <td><a href="<?php echo base_url(); ?>AdminControllerStorages/acceptStock/<?php echo $moved_product->id; ?>"><span class="fa fa-check"></span> قبول</a></td>
                    </tr>
                <?php
                        }}
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
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المنتجات المتوفرة في مخزن <?php echo $storage[0]['name']; ?></h4>
    <a href="<?php echo base_url(); ?>AdminControllerStorages/show" class="btn btn-success mx-3"><span class="fa fa-list"></span> كل المخازن</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">رقم المنتج</th>
                <th scope="col">اسم المنتج</th>
                <th scope="col">الكمية</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                $products = $this->storages_model->getByData('products','');
                    if($products){
                        //Loop All Users
                        foreach($products as $product){
                            $product = (array) $product;
                ?>
                    <tr>
                        <th scope="row"><?php
                        echo $this->cashier_model->getpid($product['created_at'],$product['barcode']);
                        ?></th>
                        <td><a href="<?php echo base_url(); ?>AdminControllerProducts/view/<?php echo $product['id']; ?>"><?php echo $product['name'].' - '.$product['color'].' - '.$product['size']; ?></a></td>
                        <td><b>
                        <?php
                            echo $this->cashier_model->getQuantity($product['created_at'],'storage',$storage[0]['id']);
                        ?>
                        </b></td>
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