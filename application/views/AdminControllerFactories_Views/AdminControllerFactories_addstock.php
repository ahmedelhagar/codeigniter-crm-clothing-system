
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">أضف كمية لـ مصنع <?php echo $factory['name']; ?></h4>
    <a href="<?php echo base_url(); ?>AdminControllerFactories/products/<?php echo $factory['id']; ?>" class="btn btn-success mx-3"><span class="fa fa-list"></span> منتجات مصنع <?php echo $factory['name']; ?></a>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php 
            if($product){
                $product = $product[0];
        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 float-right text-center">
            <img src="<?php echo base_url('public/uploads/'.$product->image); ?>" alt="<?php echo $product->name; ?>">
            <h3 class="data-block-bg">رقم المنتج</h3>
            <h5><?php echo $product->id; ?></h5>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 float-right text-right">
                <p><?php echo $product->name.' - '.$product->size.' - '.$product->color; ?></p>
                <p>السعر : <?php echo $product->wholesale_price; ?> ج.م | الكمية الحالية : <?php echo 'Q';//$product->quantity; ?> قطعة</p>
                <div class="col-12 float-right">
                        <?php
                            $attributes = array(
                                'class' => 'adminForm productForm',
                                'method' => 'POST',
                                'autocomplete' => 'off'
                            );
                                $productData = '';
                                $submitValue = 'اضافة';
                                $nextPage = 'writestock/'.$product->id.'/'.$factory['id'];
                            echo form_open_multipart('adminControllerFactories/'.$nextPage, $attributes);
                            echo $this->factories_model->processAlert('danger','validation');
                            if(isset($process) && $process){
                                echo $this->factories_model->processAlert('success','alert','تمت اضافة الكمية بنجاح');
                            }
                                //Create Form Fields
                                $this->factories_model->formFields($fields,'productForm',$productData);
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