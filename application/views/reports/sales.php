

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report
        <small>Sales</small>
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
              <h3 class="box-title">Sales Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="userTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Name</th>                  
                  <th>Catagory</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Vat</th>
                  <th>Service Charge</th>
                  <th>Discount</th>
                  <th>Total Price</th>
                  <th>Payment Satus</th>
                  <th>Date</th>
                  <th>Store</th>
                  <th>Username</th>                          
                </tr>
                </thead>
                <tbody>
                  <?php if($sales): ?>
                    <?php $count = 1; ?>                
                    <?php foreach ($sales as $k => $v): ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $v['name']; ?></td>                        
                        <td>
                          <?php 
                            $cat = json_decode($v['category_id']);
                            if(is_array($cat)){
                              foreach ($category as $kk => $vv){
                                if($vv['id'] == $cat[0])
                                  echo $vv['name'];
                              }
                            }
                            echo '-'; 
                          ?>
                        </td>
                        <td><?php echo $v['qty']; ?></td>
                        <td><?php echo $v['price']; ?></td>
                        <td><?php echo $v['vat_charge']; ?></td>
                        <td><?php echo $v['service_charge']; ?></td>
                        <td><?php echo $v['discount']; ?></td>
                        <td><?php echo $v['total_price']; ?></td>
                        <td><?php echo $v['paid_status']; ?></td>
                        <td><?php echo date('m-d-y H:i:s a', $v['date_time']); ?></td>
                        <td><?php
                          if($v['store_id']){
                            foreach($store as $_k => $_v){
                                if($_v['id'] == $v['store_id']) echo $_v['name'];
                            }
                          }
                         ?></td>
                        <td><?php echo $v['username']; ?></td>
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
      $("#salesReportsNav").addClass('active');
    });
  </script>
