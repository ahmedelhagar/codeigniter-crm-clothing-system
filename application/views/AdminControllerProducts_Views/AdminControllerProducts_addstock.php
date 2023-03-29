
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">ادارة المنقولات وتعريف المنتجات</h4>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/show" class="btn btn-success mx-3"><span class="fa fa-list"></span> كل المنتجات</a>
    <a href="<?php echo base_url(); ?>AdminControllerProducts/create" class="btn btn-success mx-3"><span class="fa fa-plus"></span> أضف منتج</a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php 
            if($product){
                $product = $product[0];
        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 float-right text-center">
            <img src="<?php echo base_url('public/uploads/'.$product->image); ?>" alt="<?php echo $product->name.' - '.$product->size.' - '.$product->color; ?>">
            <h3 class="data-block-bg">رقم المنتج</h3>
            <h5><?php
            $explodedDate = explode('.',$product->created_at);
            $explodedDate1 = explode(':',$explodedDate[0]);
            echo $product->barcode.$explodedDate1[2].substr($explodedDate[1],0,2);
            ?></h5>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 float-right text-right">
                <p><?php echo $product->name; ?></p>
                <p>السعر : <?php echo $product->wholesale_price; ?> ج.م</p>
                <p>الكمية : <?php 
                
                foreach($this->products_model->getAllQ($product->created_at)['storages'] as $storage => $quantity){
                    echo 'مخزن '.$storage.' به عدد '.$quantity.' قطعة<br>';
                }
                foreach($this->products_model->getAllQ($product->created_at)['stores'] as $store => $quantity){
                    echo 'محل '.$store.' به عدد '.$quantity.' قطعة<br>';
                }
                
                ?></p>
                <div class="col-12 float-right">
                        <?php
                            $attributes = array(
                                'class' => 'adminForm productForm',
                                'method' => 'POST',
                                'autocomplete' => 'off'
                            );
                                $productData = '';
                                $submitValue = 'اضافة';
                                $nextPage = 'writestock/'.$product->id;
                            echo form_open_multipart('adminControllerProducts/'.$nextPage, $attributes);
                            echo $this->products_model->processAlert('danger','validation');
                            if(isset($process) && $process){
                                echo $this->products_model->processAlert('success','alert','تم ارسال طلبية - وبانتظار موافقة المرسل له');
                            }elseif(isset($process) && $process < 1){
                                echo $this->products_model->processAlert('danger','alert','يبدو أن المكان الذي تريد الارسال منه لايمتلك مخزون كافي');
                            }
                                //Create Form Fields
                                $this->products_model->formFields($fields,'productForm',$productData);
                                //Submit The Form
                                echo '<br />'.form_submit('add', $submitValue,array(
                                    'class' => 'btn btn-success'
                                ));
                            echo form_close();
                        ?>
                        <br /><br />
            </div>
        <?php } ?>
    </div>
</div>