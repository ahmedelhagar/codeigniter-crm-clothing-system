<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة</title>
    <style>
    .ticket {
        width: 50mm;
        max-width: 50mm;
        text-align:center;
    }

    .ticket img {
        max-width: inherit;
        width: inherit;
    }
    .ticket img {
        width: 40mm;
        margin: auto;
        display: block;
        height: 10mm;
    }
    .btn-print {
        padding: 10px;
        background: #34be52;
        font-size: 17px;
        font-family: tahoma;
        color: #fff;
        border: 0px;
        margin-top: 10px;
    }
    .printable {
        height: 20mm;
        width: 100%;
        padding-top: 3mm;
    }
    .printable span{
        font-size: 13px;
    }
    @media print {
        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }
</style>
</head>
<body>
<div class="ticket">

    <?php
    foreach($inputs as $input){
    $id = explode('-',$input);
    $number = $this->input->post($input);
    $product = $this->products_model->getByData(
        'products',
        ' WHERE (id = '.$id[1].')'
    );
    if($product){
    $product = $product[0];
    

    $x=1;while($x <= $number){ ?>
    <div class="printable">
<span><?php echo $product['name'].' - '.$product['size'].' - '.$product['color'].' - '.$product['barcode']; ?></span></span>
<?php
    $explodedDate = explode('.',$product['created_at']);
    $explodedDate1 = explode(':',$explodedDate[0]);
    $newBarcode = $product['barcode'].$explodedDate1[2].substr($explodedDate[1],0,2);
?>
        <img src="<?php echo base_url('AdminControllerProducts/barcodeValue/'.urlencode($newBarcode)); ?>" alt="Logo">
<b style="direction:rtl;"><span><?php echo $newBarcode; ?><span style="direction:rtl;float:left;"><?php echo $product['wholesale_price'].'ج.م.'; ?></span></b>
    </div>
    <?php $x++;}}} ?>

</div>

<button id="btnPrint" class="btn-print hidden-print">اطبع</button>
<script src="<?php echo base_url(); ?>public/js/jquery.min.js"></script>
<script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
$(document).keydown(function(e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        window.close();
    }
});
</script>
</body>
</html>