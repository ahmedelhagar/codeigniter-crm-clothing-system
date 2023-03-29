<script type="text/javascript">
setCookie('currentReceipt',1,1);
setCookie('Rec-1',null,1);
setCookie('Rec-2',null,1);
setCookie('Rec-3',null,1);
setCookie('Rec-4',null,1);
setCookie('Rec-5',null,1);
setCookie('Rec-6',null,1);
setCookie('Rec-7',null,1);
setCookie('Rec-8',null,1);
setCookie('Rec-9',null,1);
setCookie('Rec-10',null,1);
setCookie('Rec-11',null,1);
setCookie('Rec-12',null,1);
setCookie('Rec-13',null,1);
setCookie('Rec-14',null,1);
setCookie('Rec-15',null,1);
setCookie('Rec-16',null,1);
setCookie('Rec-17',null,1);
setCookie('Rec-18',null,1);
setCookie('Rec-19',null,1);
setCookie('Rec-20',null,1);
$('#re-barcode').on('keypress',function() {
    var barcode = $(this).val();
    var reciepts = '';
    var trans = '';
      // your code go here
      $.ajax({
          url:"<?php echo base_url('AdminControllerCashier/searchRec/'); ?>",
          type:"POST",
          dataType: "json",
          async: true,
          data: {
              'barcode' : barcode
          },
          success:function(response){
                if(response.done == 1){
                    for (var i = 0; i < response.transaction.length; i++) {
                    if(Number(response.transaction[i]['state']) < 1){
                        refund = '<a target="_blank" class="btn btn-danger float-left" id="delete-re-'+response.transaction[i]['id']+'" onclick="return refund('+response.transaction[i]['id']+');" style="color:#fff;">\
                        مرتجع\
                        <span class="fa fa-reply"></span>\
                    </a>';
                    }else{
                        refund = '<br/><code>مرتجعة</code>';
                    }
                    if(response.client){
                        var clientName = 'العميل/ '+response.client['name']+' | ';
                    } else
                    {
                        var clientName = '';
                    }
                    trans += '<div class="product-items col-lg-12 col-md-12 col-sm-12 float-right text-right mb-3 px-2 py-2">\
            <div class="col-lg-12 col-md-12 col-sm-12 float-right px-2 py-2">\
                <h5 class="text-right float-right mb-0">\
                    العملية لـ \
                </h5><br />\
                <div class="text-muted">\
                    <div class="cust">\
                        '+clientName+' تم الدفع '+response.transaction[i]['method']+'\
                    </div>'+refund+'<a href="<?php echo base_url('AdminControllerCashier/transaction/'); ?>'+response.transaction[i]['id']+'" target="_blank" class="btn btn-success float-left" id="print-re">\
                        طباعة\
                        <span class="fa fa-print"></span>\
                    </a>\
                </div>\
            </div>\
        </div>';
                }
                $('.past-re').html(trans);
        function refund(id){
            var place_id = '<?php echo $this->uri->segment(3); ?>';
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
                    $('#delete-re-'+id).after('<br/><code>مرتجعة</code>');
                    $('#delete-re-'+id).remove();
                }
          }
    });
}
                }
                }
            })
        });
$('#barcode').change(function() {
    var barcode = $(this).val();
    var products = $('.all-items').html();
    var productsIds = '';
    var dproducts = '';
    var productsPrice = 0;
    if(getCookie('products') == null){
        setCookie('products',productsIds,1);
        setCookie('dproducts',dproducts,1);
        setCookie('price',productsPrice,1);
    }
      // your code go here
      $.ajax({
          url:"<?php echo base_url('AdminControllerCashier/searchRequest/'); ?>",
          type:"POST",
          dataType: "json",
          async: true,
          data: {
              'barcode' : barcode
          },
          success:function(response){
                if(response.done == 1){
                    productsArray = getCookie('products').split(",");
                    if(jQuery.inArray(response.product['id'], productsArray) !== -1){
                        $('input[type=number]#pinput-'+response.product['id']).attr('value',Number($('input[type=number]#pinput-'+response.product['id']).val())+1);
                        $('input[type=number]#dinput-'+response.product['id']).attr('value',Number($('input[type=number]#dinput-'+response.product['id']).val()));
                    }else{
                        $('.all-items').html('<div class="product-item" id="pi-'+response.product['id']+'" data-price="'+response.product['wholesale_price']+'"><div class="btn btn-danger float-left" id="delete-re" onclick="return removeItem('+response.product['id']+');"><span class="fa fa-times"></span></div><p>'+response.product['name']+' - '+response.product['size']+' - '+response.product['color']+' - '+response.product['wholesale_price']+'ج.م </p><input id="pinput-'+response.product['id']+'" data-price="'+response.product['wholesale_price']+'" onchange="return reCalc('+response.product['id']+');" onkeyup="return reCalc('+response.product['id']+');" onpast="return reCalc('+response.product['id']+');" type="number" class="form-control" min="1" value="1"><input id="dinput-'+response.product['id']+'" data-price="'+response.product['wholesale_price']+'" type="number" onchange="return reCalc('+response.product['id']+');" onkeyup="return reCalc('+response.product['id']+');" onpast="return reCalc('+response.product['id']+');" class="form-control" min="0" max="100" placeholder="نسبة الخصم %"></div>'+products);
                        if(getCookie('products') == null){
                            productsIds = response.product['id']+',';
                        }else{
                            productsIds = getCookie('products')+response.product['id']+',';
                        }
                        setCookie('products',productsIds,1);
                    }
                    if(getCookie('dproducts') == null){
                            dproductsIds = response.product['id']+'-'+Number($('#dinput-'+response.product['id']).val())+',';
                    }else{
                        dproductsIds = getCookie('dproducts')+response.product['id']+'-'+Number($('#dinput-'+response.product['id']).val())+',';
                    }
                    setCookie('dproducts',dproductsIds,1);
                    productsPrice = Number(getCookie('price'))+Number(response.product['wholesale_price'])-(Number(response.product['wholesale_price'])*0.01*Number($('#dinput-'+response.product['id']).val()));
                    setCookie('price',productsPrice,1);
                    $('#price').html(productsPrice);
                    $('#empty-barcodes').fadeOut(200);
                    calcTotal();
                }
              }
      });
      $(this).val('');
    });
$('#barcode').focus(function(){
    $(this).val('');
})
function removeItem(id){
    //Remove From Cookie
    var productsArray;
    productsArray = getCookie('products').split(",");
    removeFromArrayByValue(productsArray,id);
    var productsIds = productsArray.join(",");
    setCookie('products',productsIds,1);
    //Remove From Cookie
    var dproductsArray;
    dproductsArray = getCookie('dproducts').split(",");
    removeFromArrayByValue(dproductsArray,id+'-'+0);
    var dproductsIds = dproductsArray.join(",");
    setCookie('dproducts',dproductsIds,1);
    //Edit Final Price
    var itemNum = $('#pinput-'+id).val();
    var itemDisc = $('#dinput-'+id).val();
    var itemPrice = $('#pi-'+id).attr('data-price');
    var totalPrice = (Number(itemNum)*Number(itemPrice))-(Number(itemDisc)*0.01*Number(itemNum)*Number(itemPrice));
    var finalPrice = Number(getCookie('price'))-Number(totalPrice);
    if(finalPrice == null){
        setCookie('price',0,1);
        $('#price').html(0);
    }else{
        setCookie('price',finalPrice,1);
        $('#price').html(finalPrice);
    }
    calcTotal();
    //Remove Rendered Element
    $('#pi-'+id).remove();
}
$('#discount').on("change keyup paste", function() {
    calcTotal();
});
function calcTotal(){
    var discount = $('#discount').val();
    setCookie('discount',discount,1);
    var productsArray;
    var totalDiscounts = 0;
        productsArray = getCookie('dproducts').split(",");
        for (var i = 0; i < productsArray.length; i++) {
            if(productsArray[i]){
                var productsDiscs;
                    productsDiscs = productsArray[i].split("-");
                    totalDiscounts += Number($('#pinput-'+productsDiscs[0]).attr('data-price'))*0.01*Number($('#dinput-'+productsDiscs[0]).val());
            }
        }
    var totalPrice = Number($('#price').html())-Number(discount);
    $('#totalPrice').html(totalPrice);
    setCookie('totalPrice',totalPrice);
}
function reCalc(id,discount){
        var numProducts = $('#pinput-'+id).val();
        var discProducts = $('#dinput-'+id).val();
        var productsPrice = 0;
        $('#pinput-'+id).attr('value',numProducts);
        $('#dinput-'+id).attr('value',discProducts);
        var productsArray;
        productsArray = getCookie('products').split(",");
        for (var i = 0; i < productsArray.length; i++) {
            if(productsArray[i]){
                var discountValue = Number($('#dinput-'+productsArray[i]).val())*Number($('#pinput-'+productsArray[i]).val())*0.01*Number($('#dinput-'+productsArray[i]).attr('data-price'));
                productsPrice += (Number($('#pinput-'+productsArray[i]).val())*Number($('#pinput-'+productsArray[i]).attr('data-price')))-Number(discountValue);
            }
        }
        setCookie('price',productsPrice);
        $('#price').html(productsPrice);
        calcTotal();
}
function refund(id){
    var place_id = '<?php echo $this->uri->segment(3); ?>';
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
                    $('#delete-re-'+id).after('<br/><code>مرتجعة</code>');
                    $('#delete-re-'+id).remove();
                }
          }
    });
}
$('.r-add-new').click(function(){
    let defaultRec = $('.r-def').attr('num-data'), lastRec = $('.receipt:last').attr('num-data');
    $(this).before(' <div class="btn btn-primary receipt" num-data="'+(Number(lastRec)+1)+'" id="receipt"><span class="fa fa-receipt"></span>'+(Number(lastRec)+1)+'</div>');
    $('.receipt').click(function(){
        let defaultRec = $('.r-def').attr('num-data'), lastRec = $('.receipt:last').attr('num-data'), choosedRec = $(this).attr('num-data');
        let discount = getCookie('discount'), products = getCookie('products'),totalPrice = getCookie('totalPrice');
        let productsArray, productsPrice = [];
        if(getCookie('products')){
            productsArray = getCookie('products').split(",");
            for (var i = 0; i < productsArray.length; i++) {
                if(productsArray[i]){
                    var obj = {};
                    var numProducts = $('#pinput-'+productsArray[i]).val();
                    obj[i] = [Number(numProducts),Number(productsArray[i])];
                    productsPrice.push(obj);
                }
            }
        }
        $('.r-def').removeClass('btn-default');
        $('.r-def').addClass('btn-primary');
        $('.r-def').removeClass('r-def');
        $(this).removeClass('btn-primary');
        $(this).addClass('btn-default');
        $(this).addClass('r-def');
        $('.all-items').html('');
        $('#rest').html(0);
        $('#price').html(0);
        $('#totalPriceDialog').html(0);
        $('#totalPrice').html(0);
        $('#discount').val(0);
        $('#paid').val('');
        $('#paid-visa').val('');
        $('#client_mobile').val('');
        $('#client_name').val('');
        $('#client').remove();
        setCookie('currentReceipt',choosedRec);
        if(!getCookie('Rec-'+defaultRec)){
            setCookie('Rec-'+defaultRec,discount+':::'+products+':::'+totalPrice+':::'+JSON.stringify(productsPrice));
        }
        if(!getCookie('Rec'+choosedRec)){
            let valuesRec = getCookie('Rec-'+$('.r-def').attr('num-data')).split(':::');
            setCookie('discount',valuesRec[0]);
            setCookie('products',valuesRec[1]);
            setCookie('totalPrice',Number(valuesRec[2])-Number(valuesRec[0]));
            setCookie('price',Number(valuesRec[2]));
            if(valuesRec[2]){
                $('#price').html(valuesRec[2]);
                $('#totalPriceDialog').html(Number(valuesRec[2])-Number(valuesRec[0]));
                $('#totalPrice').html(Number(valuesRec[2])-Number(valuesRec[0]));
                /*
                *Change Data
                */
            let pData = JSON.parse(valuesRec[3]);
            $.ajax({
          url:"<?php echo base_url('AdminControllerCashier/getPrevious/'); ?>",
          type:"POST",
          dataType: "json",
          async: true,
          data: {
              'data' : pData,
              'choosed' : choosedRec
          },
          success:function(response){
                if(response.done == 1){
                    $('.all-items').html(response.data);
                    reCalc();
                }
            }
        })
            }
            if(valuesRec[0]){
                $('#discount').html(valuesRec[0]);
            }
        }
        /*if(!getCookie('products')){
            setCookie('Rec-'+defaultRec,null);
        }*/
    });
});
$('#pay-cash').click(function(){
    var productsArray;
    var productsPrice = [];
if($('#discount').val() > 0){
    var discount = $('#discount').val();
}else{
var discount = 0;
}
    if(getCookie('method') == 'cash+visa'){
        var byVisa = $('#paid-visa').val();
    }else{
        var byVisa = null;
    }
    var place_id = '<?php echo $this->uri->segment(3); ?>';
    productsArray = getCookie('products').split(",");
    for (var i = 0; i < productsArray.length; i++) {
        if(productsArray[i]){
            var obj = {};
            var numProducts = $('#pinput-'+productsArray[i]).val();
            var discProduct = $('#dinput-'+productsArray[i]).val();
            obj[i] = [Number(numProducts),Number(productsArray[i]),Number(discProduct)];
            productsPrice.push(obj);
        }
    }
    // your code go here
    $.ajax({
          url:"<?php echo base_url('AdminControllerCashier/pay/'); ?>",
          type:"POST",
          dataType: "json",
          async: true,
          data: {
              'method' : getCookie('method'),
              'data' : productsPrice,
              'place_id' : place_id,
              'byVisa' : byVisa,
              'discount' : discount
          },
          success:function(response){
                if(response.done == 1){
                    if(response.client){
                        var clientName = 'العميل '+response.client['name']+' | ';
                    } else
                    {
                        var clientName = '';
                    }
                    $('.past-re').html('\
        <div class="product-items col-lg-12 col-md-12 col-sm-12 float-right text-right mb-3 px-2 py-2">\
            <div class="col-lg-12 col-md-12 col-sm-12 float-right px-2 py-2">\
                <h5 class="text-right float-right mb-0">\
                    العملية لـ \
                </h5><br />\
                <div class="text-muted">\
                    <div class="cust">\
                        '+clientName+'تم الدفع '+response.transaction['method']+'\
                    </div>\
                    <a target="_blank" class="btn btn-danger float-left" id="delete-re-'+response.transaction['id']+'" onclick="return refund('+response.transaction['id']+');" style="color:#fff;">\
                        مرتجع\
                        <span class="fa fa-reply"></span>\
                    </a>\
                    <a  href="<?php echo base_url('AdminControllerCashier/transaction/'); ?>'+response.transaction['id']+'" target="_blank" class="btn btn-success float-left" id="print-re">\
                        طباعة\
                        <span class="fa fa-print"></span>\
                    </a>\
                </div>\
            </div>\
        </div>'+$('.past-re').html());
                    window.open('<?php echo base_url('AdminControllerCashier/transaction/'); ?>'+response.transaction['id'],'_blank');
                    resetCashier();
                }
          }
    });
});
function resetCashier(){
    eraseCookie('client');
    eraseCookie('totalPrice');
    eraseCookie('discount');
    eraseCookie('price');
    eraseCookie('products');
    eraseCookie('dproducts');
    eraseCookie('method');
    $('.all-items').html('');
    $('#rest').html(0);
    $('#price').html(0);
    $('#totalPriceDialog').html(0);
    $('#totalPrice').html(0);
    $('#discount').val(0);
    $('#paid').val('');
    $('#paid-visa').val('');
    $('#client_mobile').val('');
    $('#client_name').val('');
    setCookie('Rec-'+getCookie('currentReceipt'),null);
    $('#cashDialog').modal('toggle');
    $('#client').remove();
}
$('#cashDialogBtn').click(function(){
    $('#totalPriceDialog').html(getCookie('totalPrice'));
    $('.case').fadeIn();
    $('.byVisa').fadeOut();
    setCookie('method','cash',1);
});
$('#visaDialogBtn').click(function(){
    $('#totalPriceDialog').html(getCookie('totalPrice'));
    setCookie('method','visa',1);
    $('.case').fadeOut();
    $('.byVisa').fadeOut();
    $('#cashDialog').modal('toggle');
});
$('#mergedDialogBtn').click(function(){
    $('#totalPriceDialog').html(getCookie('totalPrice'));
    $('.byVisa').fadeIn();
    $('.case').fadeIn();
    setCookie('method','cash+visa',1);
    $('#cashDialog').modal('toggle');
});
$('#paid').on("change keyup paste", function(){
    $('#rest').html(Number($(this).val())-Number(getCookie('totalPrice')));
});
$('#client_mobile').on("change keyup paste", function(){
    var letters = $(this).val().length;
    var mobile = $(this).val();
    if(letters == 11){
        //Search For The Client
        getClient(mobile);
    }
});
function getClient(mobile){
    // your code go here
    $.ajax({
            url:"<?php echo base_url('AdminControllerCashier/getClient/'); ?>",
            type:"POST",
            dataType: "json",
            async: true,
            data: {
                'method' : 'search',
                'mobile' : mobile
            },
            success:function(response){
                    if(response.done == 1){
                        $('#client').remove();
                        $('<div onclick="return chooseClient('+response.client.id+');" class="client-'+response.client.id+'" id="client"><h5>'+response.client.name+'</h5><p class="mb-0">'+response.client.mobile+'</p></div>').insertAfter( "#register_client" );
                        $('#register_client').html('');
                    }else{
                        $('#client').remove();
                        $('#register_client').html('<input type="text" id="client_name" placeholder="اسم العميل" class="form-control"><br /><div id="addClient" class="btn btn-success" onclick="return addClient();"><span class="fa fa-plus"></span> أضف العميل</div>');
                    }
            }
        });
}
function addClient(){
    var mobile = $('#client_mobile').val();
    var name = $('#client_name').val();
    // your code go here
    $.ajax({
            url:"<?php echo base_url('AdminControllerCashier/getClient/'); ?>",
            type:"POST",
            dataType: "json",
            async: true,
            data: {
                'method' : 'add',
                'mobile' : mobile,
                'name' : name
            },
            success:function(response){
                    if(response.done == 1){
                        $('#client').remove();
                        $('<div onclick="return chooseClient('+response.client.id+');" class="client-'+response.client.id+'" id="client"><h5>'+response.client.name+'</h5><p class="mb-0">'+response.client.mobile+'</p></div>').insertAfter( "#register_client" );
                        $('#register_client').html('');
                    }
            }
        });
};
function chooseClient(id){
    var currentClass = $('.client-'+id).attr('class');
    if(currentClass == 'client-'+id){
        $('.client-'+id).attr('class','btn-success client-'+id);
        setCookie('client',id,1);
    }else{
        $('.client-'+id).attr('class','client-'+id);
        eraseCookie('client');
    }
};
</script>