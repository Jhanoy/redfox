

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report
        <small>Employees Sales Report</small>
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
              <h3 class="box-title">Employee Sales Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="userTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>User ID</th>                  
                  <th>Username</th>
                  <th>With VAT</th>
                  <th>Without VAT</th>
                  <th>Total sales</th>                         
                </tr>
                </thead>
                <tbody>
                  <?php if($user_sales): ?>
                    <?php $count = 1; ?>                
                    <?php foreach ($user_sales as $k => $v): ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $v['user_id']; ?></td>                        
                        <td><?php echo $v['username']; ?></td>
                        <td><?php echo $v['WITHVAT']; ?></td>
                        <td><?php echo $v['WITHOUTVAT']; ?></td>
                        <td><?php echo $v['WITHVAT'] + $v['WITHOUTVAT'] ?></td>
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
      $("#employeeSalesReportsNav").addClass('active');
    });
  </script>
