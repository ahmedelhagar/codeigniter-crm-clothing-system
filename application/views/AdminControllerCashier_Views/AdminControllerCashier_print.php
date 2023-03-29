<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة</title>
    <style>
td,
th,
tr,
table {
    border: 1px solid black;
    border-collapse: collapse;
    font-size: 16px;
    box-shadow: 1px 1px 5px 1px #5e5e5e57;
}

td.description,
th.description {
    width: 80mm;
    max-width: 80mm;
    font-size: 16px;
}

td.quantity,
th.quantity {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
    font-size: 16px;
}

td.price,
th.price {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
    font-size: 12px;
    text-align: center;
    align-content: center;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    width: 80mm;
    max-width: 80mm;
}

img {
    max-width: inherit;
    width: inherit;
}
img {
    width: 50mm;
    margin: auto;
    display: block;
}
.danger-btn {

padding: 10px;

background: #be3434;

font-size: 17px;

font-family: tahoma;

color: #fff;

border: 0px;
cursor: pointer;

}
.rubber {

box-shadow: 0 0 0 3px blue, 0 0 0 2px #eaf5ec inset;

border: 2px solid transparent;

border-radius: 4px;

display: inline-block;

padding: 10px 10px;

line-height: 22px;

color: blue;

font-size: 18px;

font-family: tahoma;

text-transform: uppercase;

text-align: center;

opacity: 0.4;

width: auto;

transform: rotate(-5deg);

position: absolute;

top: 140px;

right: 60px;

}
.btn-print {
        padding: 10px;
        background: #34be52;
        font-size: 17px;
        font-family: tahoma;
        color: #fff;
        border: 0px;
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
<?php $state=(int) $transaction[0]['state'];if($state == 1){ ?>

<div class="rubber">

    مُرتجع

</div>

<?php } ?>
            <p class="centered">
                <img src="<?php echo base_url('public/images/print.png'); ?>" alt="كوكي">
                <b>محلات كوكي - فرع <?php echo $store['name']; ?></b>
                <br>وصل رقم : <?php echo str_replace(array('.','-',':',' '),array('','','',''),$transaction[0]['created_at']); ?>
                <br>التوقيت : <?php echo explode('.',$transaction[0]['created_at'])[0]; ?>
        <br>الكاشير : <?php 
            $user = $this->cashier_model->getByData('users',' WHERE (id = '.$transaction[0]['u_id'].')');
            echo $user[0]['name'];
        ?>
        </p>
            <table>
                <thead>
                    <tr>
                        <th class="i">م</th>
                        <th class="description">المنتج</th>
                        <th class="quantity">كمية</th>
                        <th class="price">سعر</th>
                        <th class="discount">خصم</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $items = explode(',',$transaction[0]['items']);
                $discounts = explode(',',$transaction[0]['discounts']);
                $i = 1;
                foreach($items as $item){
                    if($item){
                        $itemData = explode('NxId',$item);
                    ?>
                    <tr>
                    <?php
                            $product = $this->cashier_model->getByData('products',' WHERE (created_at = \''.$this->cashier_model->p_created_at($itemData[1]).'\')');
                        ?>
                        <td class="i centered"><?php echo $i; ?></td>
                        <td class="description centered"><?php
                            echo $product[0]['name'].' - '.$product[0]['color'].' - '.$product[0]['size'].'<br>';
                            echo $this->cashier_model->getpid($product[0]['created_at'],$product[0]['barcode']);
                        ?></td>
                        <td class="quantity centered"><?php echo $itemData[0]; ?> X </td>
                        <td class="price centered"><?php echo $product[0]['wholesale_price']; ?></td>
                        <td class="discount centered"><?php echo $discounts[$i-1]; ?>%</td>
                    </tr>
                <?php }$i++;} ?>
                </tbody>
            </table>
            <p class="centered">
                <div class="pay mt-0 text-right">
                    <div style="margin-right:5px;" class="pd">الإجمالي : <?php echo $transaction[0]['price']; ?> ج.م</div>
                    <?php
                        if($transaction[0]['discount']){
                    ?>
                    <div class="pd" style="
    border: 2px solid #434343;
    padding-bottom: 10px;
    margin-bottom: 10px;
    float: right;
    width: 100%;
    margin-right:5px;
    margin-top:5px;
    padding: 5px;
">خصم على الإجمالي : <?php echo $transaction[0]['discount']; ?> ج.م</div>
                    <div style="margin-right:5px;" class="pd">المدفوع : <?php echo $transaction[0]['price']-$transaction[0]['discount']; ?> ج.م</div>
                    <?php
                        }
                    ?>
                    <?php 
        if($transaction[0]['byvisa']){
            echo 'تم دفع مبلغ '.$transaction[0]['byvisa'].'ج.م بالفيزا';
        }
        if($transaction[0]['method'] == 'visa'){
            echo 'تم دفع المبلغ كامل بالفيزا';
        }
        ?>
            </div><br />
            </p>
        </div>
<button id="btnPrint" class="btn-print hidden-print">اطبع</button>
<?php $state=(int) $transaction[0]['state'];if($state !== 1){
    $storesss = $this->cashier_model->getByData('stores','');
    foreach ($storesss as $storess) {
    ?>

<a target="_blank" class="danger-btn float-left hidden-print" id="delete-re-<?php echo $transaction[0]['id']; ?>" onclick="return refund(<?php echo $transaction[0]['id']; ?>,<?php echo $storess['id']; ?>);" style="color:#fff;">
        مرتجع من فرع <?php echo $storess['name']; ?>
        <span class="fa fa-reply"></span>
</a>

<?php }} ?>
<script src="<?php echo base_url(); ?>public/js/jquery.min.js"></script>
<script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
    window.onkeyup = function(e) {
    var event = e.which || e.keyCode || 0; // .which with fallback

    if (event == 27) { // ESC Key
        window.close();
    }
}
function refund(id,place_id){
            $.ajax({
                url:"<?php echo base_url('AdminControllerCashier/refund/'); ?>",
                type:"POST",
                dataType: "json",
                async: true,
                data: {
                    'transaction' : id,
                    'place_id' : place_id
          },
          success:function(response){
                if(response.done == 1){
                    location.reload();
                }
          }
    });
}
</script>
</body>
</html>