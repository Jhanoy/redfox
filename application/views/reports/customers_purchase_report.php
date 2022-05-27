

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report
        <small>Customers Purchase History</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reports</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12 col-xs-12">
          
         
            <button class="btn btn-primary" onclick="print()">print</button>
            <br /> <br />


          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customers Purchase History</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="userTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Customer ID</th>                  
                  <th>Customer Name</th>
                  <th>Total VAT</th>
                  <th>Total Non-VAT</th>
                  <th>Credit purchase</th>
                  <th>Cash purchase</th>                          
                  <th>Purchase No.</th>
                </tr>
                </thead>
                <tbody>
                  <?php if($customers_report): ?>
                    <?php $count = 1; ?>                
                    <?php foreach ($customers_report as $k => $v): ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $v['customer_id']; ?></td>                        
                        <td><?php echo $v['customer_name']; ?></td>
                        <td><?php echo $v['with_vat']; ?></td>
                        <td><?php echo $v['with_out_vat']; ?></td>
                        <td><?php echo $v['credit_purchase']; ?></td>
                        <td><?php echo $v['cash_purchase']; ?></td>
                        <td><?php echo $v['total_purchase']; ?></td>
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

      $("#mainReportsNav").addClass('active');
      $("#userSalesReportsNav").addClass('active');
    });
  </script>
