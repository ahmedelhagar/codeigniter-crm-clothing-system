

                </div>

</div>

<footer class="mt-3 no-print" style="

width: 100%;

float: right;

text-align: center;

position: relative;

bottom: 0px;

padding: 20px;

">

<p class="mb-0">جميع الحقوق محفوظة لشركة كوكي لملابس الأطفال</p>

<p class="mb-0">المبرمج والصيانة في حالة الضرورة القصوى تواصل مع أحد المديرين وهو سيقوم بإبلاغنا فوراً</p>

</footer>

</div>

</div>

<?php $this->load->view('holders/inPage_Scripts');

$controller = (string) $this->uri->segment(1);

if($controller == 'AdminControllerProducts' && ($this->uri->segment(2) == 'barcode')){

  $this->load->view('holders/ajax/barcodePrint');
}
?>



<?php

//Ajax Requests



if($controller == 'AdminControllerCashier'){

$this->load->view('holders/ajax/cashierAjax');

}elseif($controller == 'AdminControllerProducts' && ($this->uri->segment(2) == 'create' OR $this->uri->segment(2) == 'write' OR $this->uri->segment(2) == 'edit')){

  $this->load->view('holders/ajax/barcodePrint');

?>


<script src="<?php echo base_url('public/js/webcamjs-master/webcam.js'); ?>"></script>

<!-- Configure a few settings and attach camera -->

<script language="JavaScript">
Webcam.set({

width: 490,

height: 390,

image_format: 'jpeg',

jpeg_quality: 90

});



Webcam.attach( '#my_camera' );



function take_snapshot() {

Webcam.snap( function(data_uri) {

$(".image-tag").val(data_uri);

document.getElementById('results').innerHTML = '<img id="product-image" src="'+data_uri+'"/>';

} );

$.ajax({

url:"<?php echo base_url('AdminControllerProducts/saveImage/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'data_uri' : $('#product-image').attr('src')

},

success:function(response){

        if(response.done == 1){

            $('#productForm-3-image').attr('value',response.image);

        }

    }

});

}

</script>

<?php }elseif($this->uri->segment(2) == 'messages' AND ($controller == 'AdminControllerUsers' OR $controller == 'adminControllerUsers')){
?>
<script>
$.ajax({

url:"<?php echo base_url('Api/mapMessages/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'mapMessages'

},

success:function(response){

        if(response.done == 1){

            let messages = response.messages, msgs = '',myId = '<?php echo $this->session->userdata('id'); ?>';

            for(message of messages){
              if(message['from_id'] == myId){
                msgs += '<div class="fromMe">'+message['message']+'<div class="userProfile"><h6>من '+message['from']+' إلى '+message['to']+' في '+message['created_at'].split('.')[0]+'</h6></div></div>';
              }else{
                msgs += '<div class="toMe">'+message['message']+'<div class="userProfile"><h6>من '+message['from']+' إلى '+message['to']+' في '+message['created_at'].split('.')[0]+'</h6></div></div>';
              }
            }
            $('#allMessages').html(msgs);
        }

    },

error:function(response){

  console.log(response);

}

})
</script>
<?php
} ?>

<script language="JavaScript">

function sync(){

$('.sync').fadeIn(1000);

movestock_update();

stock_update();

transaction_update();

move_update();

recive_update();

product_update();

balance_update();

store_update();

user_update();

storage_update();


client_update();

}

function update(){

$('.sync').fadeIn(1000);

files_update();

}

function movestock_update(){

$.ajax({

url:"<?php echo base_url('Api/movestock_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'movestock_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول المنقولات</h5>');

        }

    },

error:function(response){

  console.log(response);

}

});

}

$('#notfToggle').click(function(){
  $.ajax({

    url:"<?php echo base_url('Api/mapMessages/5'); ?>",
    
    type:"POST",
    
    dataType: "json",
    
    async: true,
    
    data: {
    
      'order' : 'mapMessages'
    
    },
    
    success:function(response){
    
            if(response.done == 1){
    
                let messages = response.messages, msgs = '';
    
                for(message of messages){
                  msgs += '<li><span class="msgFrom"><span class="fa fa-user"></span> '+message['from']+':</span>'+message['message']+'</li>';
                }
                $('#messagesData').html(msgs);
                $('.notfData').toggle(300);
            }
    
        },
    
    error:function(response){
    
      console.log(response);
    
    }
    
    })
});

(function notfs(){
$.ajax({

url:"<?php echo base_url('Api/mapMessages/100'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'mapMessages'

},

success:function(response){

        if(response.done == 1){

            let messages = response.messages, nums = 0;

            for(message of messages){
              (message['seen'] == '0') ? nums += 1 : nums = nums;
            }
            if(nums >= 100){
              $('#notfs-num').html('+100');
            } else{
              $('#notfs-num').html(nums);
            }
        }

    },

error:function(response){

  console.log(response);

}

}).then(function() {           // on completion, restart
       setTimeout(notfs, 30000);  // function refers to itself
    });
})();

function stock_update(){

$.ajax({

url:"<?php echo base_url('Api/stock_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'stock_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول المخزون</h5>');

        }

    },

error:function(response){

  console.log(response);

}

});

}
function move_update(){

$.ajax({

url:"<?php echo base_url('Api/move_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'move_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول الكميات</h5>');

        }

    },

error:function(response){

  console.log(response);

}

});

}
function recive_update(){

$.ajax({

url:"<?php echo base_url('Api/updateData/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'move_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث كل الجداول</h5>');

        }

    },

error:function(response){

  console.log(response);

}

});

}
function transaction_update(){

$.ajax({

url:"<?php echo base_url('Api/transaction_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'transaction_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول التعاملات والفواتير</h5>');

        }

    },

error:function(response){

  console.log(response);

}

});

}

function product_update(){

$.ajax({

url:"<?php echo base_url('Api/product_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

'order' : 'product_update'

},

success:function(response){

if(response.done == 1){

    $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول المنتجات</h5>');

}

},

error:function(response){

console.log(response);

}

});

}

function store_update(){

$.ajax({

url:"<?php echo base_url('Api/store_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

'order' : 'store_update'

},

success:function(response){

if(response.done == 1){

    $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول الفروع</h5>');

}

},

error:function(response){

console.log(response);

}

});

}
function storage_update(){

$.ajax({

url:"<?php echo base_url('Api/storage_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

'order' : 'storage_update'

},

success:function(response){

if(response.done == 1){

    $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول المخازن</h5>');

}

},

error:function(response){

console.log(response);

}

});

}
function balance_update(){

$.ajax({

url:"<?php echo base_url('Api/balance_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

'order' : 'balance_update'

},

success:function(response){

if(response.done == 1){

    $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول الأرصدة</h5>');

}

},

error:function(response){

console.log(response);

}

});

}
function user_update(){

$.ajax({

url:"<?php echo base_url('Api/user_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

'order' : 'user_update'

},

success:function(response){

if(response.done == 1){

    $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول الأعضاء</h5>');

}

},

error:function(response){

console.log(response);

}

});

}

function client_update(){

$.ajax({

url:"<?php echo base_url('Api/client_update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'client_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث جدول العملاء</h5>');

        }

        setTimeout(function () {

            $('.sync').fadeOut(1000);

            $('.stats').html('بانتظار رد السيرفر...');

        }, 3000);

    },

error:function(response){

  console.log(response);

}

});

}

function files_update(){

$.ajax({

url:"<?php echo base_url('update/'); ?>",

type:"POST",

dataType: "json",

async: true,

data: {

  'order' : 'files_update'

},

success:function(response){

        if(response.done == 1){

            $('.stats').html($('.stats').html()+'<h5><span class="fa fa-check"></span> تم تحديث ملفات النظام بنجاح</h5>');

        }

        setTimeout(function () {

            $('.sync').fadeOut(1000);

            $('.stats').html('بانتظار رد السيرفر...');

        }, 3000);

    },

error:function(response){

  console.log(response);

}

});

}
</script>

</body>

</html>