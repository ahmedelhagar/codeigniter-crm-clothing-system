    <!-- Bootstrap core JavaScript -->
  <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>public/js/home.js"></script>
  <script src="<?php echo base_url(); ?>public/chart/dist/chart.min.js"></script>
  <script src="<?php echo base_url(); ?>public/chart/utils.js"></script>
<?php
if($this->uri->segment(1) == NULL){
    $this->load->view('holders/homeChart');
}elseif($this->uri->segment(1) == 'cp' && $this->uri->segment(2) == 'index'){
    $this->load->view('holders/homeChart');
}elseif($this->uri->segment(1) == 'AdminControllerProducts' && $this->uri->segment(2) == 'view'){
    $this->load->view('holders/productChart');
}elseif($this->uri->segment(1) == 'adminControllerUsers' && $this->uri->segment(2) == 'view'){
    $this->load->view('holders/userChart');
}
?>
<script>
$('.productForm input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});
</script>