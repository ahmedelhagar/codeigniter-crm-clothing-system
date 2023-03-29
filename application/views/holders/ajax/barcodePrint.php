<!--Barcode-->
<script type="text/javascript">
'use strict'
<?php if($products){
   foreach($products as $product){
        echo "let p_".$this->products_model->getpid($product['created_at'],str_replace(array('`','*','\'','\"','-','+','.'),array('_comma','_astr','_sco','_dco','_dash','_plus','_dot'),$product['barcode']))." = {
           id: '".$product['id']."',
           name: '".$product['name']."',
           created_at: '".$product['created_at']."',
           barcode: '".$product['barcode']."',
           color: '".$product['color']."',
           size: '".$product['size']."',
           wholesale_price: '".$product['wholesale_price']."',
        };
        ";
   }
} ?>
$('#barcode').change(function() {
    let barcode = $(this).val() ,productData = eval("p_"+((((((barcode.replaceAll("`", "_comma")).replaceAll("*", "_astr")).replaceAll("'", "_sco")).replaceAll("\"", "_dco")).replaceAll("-", "_dash")).replaceAll("+", "_plus")).replaceAll(".", "_dot"));
    $('.choosed-products').html($('.choosed-products').html()+'<div class="col-lg-6 col-md-6 col-sm-12 py-0 float-right text-center choosed-product" id="p-'+productData.id+'">\
                    <h5>'+productData.name+' - '+productData.color+' - '+productData.size+'</h5>\
                    <p class="mb-0">'+barcode+'</p>\
                    <p>السعر : '+productData.wholesale_price+'ج.م</p>\
                    <input onkeyup="return changeVal(\'p-'+productData.id+'\',this);" type="number" name="p-'+productData.id+'" min="0" placeholder="0">\
                </div>');
    $('#list-'+productData.id).remove();
    $(this).val('');
})
function changeVal(iname,id){
    $('input[name="'+iname+'"]').attr('value',id.value);
}
</script>