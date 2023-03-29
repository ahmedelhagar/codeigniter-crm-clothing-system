
<div class="admin-stats col-lg-12 col-md-12 col-sm-12 float-right text-right mt-4 pb-0">
    <h4 class="text-center">المنتجات</h4>
    <a href="<?php echo base_url(); ?>adminControllerProducts/show" class="btn btn-success mx-3"><span class="fa fa-users"></span> كل المنتجات</a>
    <?php
        if(isset($user) && $user){
    ?>
    <a href="<?php echo base_url(); ?>adminControllerProducts/create" class="btn btn-success mx-3"><span class="fa fa-plus"></span> أضف منتج</a>
    <?php } ?>
    <div class="container-fluid float-right px-3 pt-3 pb-0">
        <?php
            $attributes = array(
                'class' => 'adminForm productForm',
                'method' => 'POST',
                'autocomplete' => 'off'
            );
            if(isset($product) && $product){
                $productData = $product[0];
                $submitValue = 'أضف';
                $nextPage = 'write/'.$productData->id;
            }
            echo form_open_multipart('adminControllerProducts/'.$nextPage, $attributes);
            echo $this->products_model->processAlert('danger','validation');
            if(isset($process) && $process){
                echo $this->products_model->processAlert('success','alert','تم انشاء المنتج بنجاح.');
            }
                //Create Form Fields
                $this->products_model->formFields($fields,'productForm',$productData);
                //Submit The Form
                echo '<br />'.form_submit('add', $submitValue,array(
                    'class' => 'btn btn-success'
                ));
            echo form_close();
        ?>
        <br />
    </div>
</div>
<style>
#productForm-1-upload,#productForm-1-barcode,#productForm-2-name,#productForm-3-image,#productForm-1-source {
    display: none;
}
</style>
<script>
    let hiddenInputs = ['productForm-1-upload','productForm-1-barcode','productForm-2-name','productForm-3-image','productForm-1-source'];
    for(hIn of hiddenInputs)
        $('label[for="'+hIn+'"]').hide();
</script>