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
                <b>ادارة مصانع كنوز - منقولات المصانع</b>
                <br>نقلة رقم : <?php echo $movestock['id']; ?>
                <br>التوقيت : <?php echo explode('.',$movestock['created_at'])[0]; ?>
                <br><?php echo $office['name']; ?>
                <br><?php echo $office['address']; ?></p>
            <p class="centered">
                قام (<?php
                $user1 = $this->offices_model->getByData('users',' WHERE (id = '.$movestock['u_id'].')');
                echo $user1[0]['id'].' - '.$user1[0]['name'];
                ?>) بإنشاء طلب النقل و
                قام (<?php
                $user2 = $this->offices_model->getByData('users',' WHERE (id = '.$movestock['with_u_id'].')');
                echo $user2[0]['id'].' - '.$user2[0]['name'];
                ?>) بالنقل
                وقام (<?php
                $user3 = $this->offices_model->getByData('users',' WHERE (id = '.$movestock['with_u_id'].')');
                echo $user3[0]['id'].' - '.$user3[0]['name'];
                ?>) بإستلام الطلبية
            </p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">الكمية</th>
                        <th class="description">اسم المنتج</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="quantity centered"><?php echo $movestock['quantity']; ?></td>
                        <td class="description centered"><?php
                            $product = $this->offices_model->getByData('products',' WHERE (id = '.$movestock['p_id'].')');
                            echo $product[0]['name'].' - '.$product[0]['color'].' - '.$product[0]['size'];
                        ?></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered"><b>* يحق للمديرين فقط طلب هذا الايصال منك في أي وقت</b>
                <br>قم بطباعة 3 نسخ واحدة لـ <?php echo $user1[0]['name']; ?> والثانية لـ <?php echo $user2[0]['name']; ?> والثالثة لـ <?php echo $user3[0]['name']; ?></p>
        </div>
<button id="btnPrint" class="btn-print hidden-print">اطبع</button>
<a href="<?php echo base_url('AdminControllerOffices/products/'.$office['id']); ?>" class="btn-print hidden-print">رجوع للمكتب</a>
<script src="<?php echo base_url(); ?>public/js/jquery.min.js"></script>
<script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });
    window.onkeyup = function(e) {
    var event = e.which || e.keyCode || 0; // .which with fallback

    if (event == 27) { // ESC Key
        window.location.href = '<?php echo base_url('AdminControllerOffices/products/'.$office['id']); ?>'; // Navigate to URL
    }
}
</script>
</body>
</html>