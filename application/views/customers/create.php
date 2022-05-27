

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manage
        <small>Customers</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customers</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          
          <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
          <?php elseif($this->session->flashdata('error')): ?>
            <div class="alert alert-error alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
          <?php endif; ?>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add Customer</h3>
            </div>
            <form role="form" action="<?php base_url('customers/create') ?>" method="post">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="fullname">Full Name</label>
                  <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" autocomplete="off">
                  <?php echo form_error('fullname'); ?>
                </div>

                <div class="form-group">
                  <label for="address">Address</label>
                  <input type="address" class="form-control" id="address" name="address" placeholder="Address" autocomplete="off">
                  <?php echo form_error('address'); ?>
                </div>
                
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off">
                  <?php echo form_error('email'); ?>
                </div>

                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" autocomplete="off">
                  <?php echo form_error('phone'); ?>
                </div>

                <div class="form-group">
                  <label for="tin">TIN</label>
                  <input type="text" class="form-control" id="tin" name="tin" placeholder="TIN" autocomplete="off">
                  <?php echo form_error('tin'); ?>
                </div>                

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="<?php echo base_url('customers/') ?>" class="btn btn-warning">Back</a>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        <!-- col-md-12 -->
      </div>
      <!-- /.row -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
  $(document).ready(function() {
    $("#groups").select2();

    $("#mainCustomerNav").addClass('active');
    $("#addCustomerNav").addClass('active');
  
  });

  var forms = document.querySelectorAll('input');
  var remember = <?php echo json_encode($remember); ?>;

  forms.forEach(field => {
    field.value = remember[field.name];
  })
  console.log(remember);

// var value = select.options[select.selectedIndex].value

</script>
