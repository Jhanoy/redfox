

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
          
          <?php if(in_array('createCustomer', $user_permission)): ?>
            <a href="<?php echo base_url('customers/create') ?>" class="btn btn-primary">Add Customers</a>
            <br /> <br />
          <?php endif; ?>


          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Customers</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="manageTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>TIN</th>

                  <?php if(in_array('updateUser', $user_permission) || in_array('deleteUser', $user_permission)): ?>
                  <th>Action</th>
                  <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                  <?php if($customer_data): ?>                  
                    <?php foreach ($customer_data as $k => $v): ?>
                      <tr>
                        <td><?php echo $v['customer_info']['fullname']; ?></td>
                        <td><?php echo $v['customer_info']['email']; ?></td>
                        <td><?php echo $v['customer_info']['phone']; ?></td>
                        <td><?php echo $v['customer_info']['tin']; ?></td>

                        <?php if(in_array('updateCustomer', $user_permission) || in_array('deleteCustomer', $user_permission)): ?>

                        <td>
                          <?php //if(in_array('updateCustomer', $user_permission)): ?>
                            <!-- <a href="<?php ///echo base_url('Customers/edit/'.$v['customer_info']['id']) ?>" class="btn btn-default"><i class="fa fa-edit"></i></a> -->
                          <?php //endif; ?>
                          <?php if(in_array('deleteCustomer', $user_permission)): ?>
                            <a href="<?php echo base_url('Customers/delete/'.$v['customer_info']['id']) ?>" class="btn btn-default"><i class="fa fa-trash"></i></a>
                          <?php endif; ?>
                        </td>
                      <?php endif; ?>
                      </tr>
                    <?php endforeach ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
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
      $('#CustomerTable').DataTable();

      $("#mainCustomerNav").addClass('active');
      $("#manageCustomerNav").addClass('active');
        manageTable = $('#manageTable').DataTable();
    });

  </script>
