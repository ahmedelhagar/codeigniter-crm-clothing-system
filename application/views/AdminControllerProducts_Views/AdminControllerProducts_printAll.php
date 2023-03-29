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
    border-top: 1px solid black;
    border-collapse: collapse;
    font-size: 16px;
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
    width: 40mm;
    margin: auto;
    display: block;
    border-radius: 50px;
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
            <p class="centered">
                <img src="<?php echo base_url('public/images/logo.png'); ?>" alt="كوكي">
                <b>ادارة مصانع كنوز - المنقولات</b>
                <br>نقلة رقم : <?php echo $moveIds; ?>
                <br>التوقيت : <?php echo explode('.',$movestocks[0]['edited_at'])[0]; ?>
                <br><?php echo $place['name']; ?>
                <br><?php echo $place['address']; ?></p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">الكمية</th>
                        <th class="description">اسم المنتج</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($movestocks as $movestockData){ ?>
                    <tr>
                        <td class="quantity centered"><?php echo $movestockData['quantity']; ?></td>
                        <td class="description centered"><?php
                            $product = $this->products_model->getByData('products',' WHERE (id = '.$movestockData['p_id'].')');
                            echo $product[0]['name'];
                        ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <p class="centered"><b>* يحق للمديرين فقط طلب هذا الايصال منك في أي وقت</b>
        </div>
<button id="btnPrint" class="btn-print hidden-print">اطبع</button>
<a href="<?php echo base_url('AdminController'.$dplace.'s/products/'.$dplace_id); ?>" class="btn-print hidden-print">اغلاق</a>
<script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
</script>
</body>
</html>