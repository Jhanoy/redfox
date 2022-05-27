

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report
        <small>Products</small>
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
              <h3 class="box-title">Products Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="userTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Category</th>                  
                  <th>P-Name</th>
                  <th>Attribute</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Store</th>
                </tr>
                </thead>
                <tbody>
                  <?php if($products): ?>
                    <?php $count = 1; ?>                
                    <?php foreach ($products as $k => $v): ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
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
                        <td><?php echo $v['name']; ?></td>
                        <td><?php
                          $att = json_decode($v['attribute_value_id']);
                          if(is_array($att)){
                            // echo 'TRUE';
                            foreach ($att as $key => $val){
                                // echo $val;
                                // print_r($attributes);
                              foreach ($attributes as $_k => $_v){
                                if($_v['id'] == $val) echo '<span style="display: block;">'.$_v['value'].'</span>';                              
                              }
                            }
                          }
                         ?></td>
                        <td><?php echo $v['price']; ?></td>
                        <td><?php echo $v['qty']; ?></td>
                        <td><?php echo $v['store_name']; ?></td> 
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
      $("productsReportsNav").addClass('active');
    });
  </script>
