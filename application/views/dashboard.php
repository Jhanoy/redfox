

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <?php if($is_admin == true): ?>

        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $total_products ?></h3>

                <p>Total Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="<?php echo base_url('products/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $total_paid_orders ?></h3>

                <p>Total Paid Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="<?php echo base_url('orders/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->          
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php echo $credit_sales['tot_credit'] ?></h3>

                <p>Total Credit Sales</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-home"></i>
              </div>
              <a href="<?php echo base_url('stores/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>          
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo $total_users; ?></h3>

                <p>Total Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-people"></i>
              </div>
              <a href="<?php echo base_url('users/') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- ./col -->
        </div>
        <!-- /.row -->
      <?php endif; ?>
        <div class="row"> <!-- ////////////////////// THIS IS IT ///////////// -->
          <div class="col-md-8">
              <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class='fa fa-bell'> </i> Notifications</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div style="padding-bottom: 20px;" class="box-body" id='productBody'>
                    
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </div><!-- ///////////// THIS IS THE END ////////////// -->
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script type="text/javascript">
    $(document).ready(function() {
      $("#dashboardMainMenu").addClass('active');
    }); 

    async function notify(){
      return true;
    }

  async function productFetch(action=''){
      const _action = action;
    if (_action == 'fetch') {
      const rawResponse = await fetch('https://stock.shillingfintech.com/products/fetchProductData', 
      {
        method: "GET",
        headers: {
          'Content-Type': 'application/json',
          // 'Accept': 'application/json'
        },
      });
      const content = await rawResponse.json();
      const statusCode = await rawResponse.status;
  
      console.log(statusCode);
      console.log(content);
      
      var t = "";
      for (var i = 0; i < content.data.length; i++){
            if(!Number(content.data[i][4])){
              var tr = "<div class='alert alert-danger alert-dismissable'>";
              tr += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
              tr += "<h4><i class='icon fa fa-ban'></i> Alert!</h4>";
              tr += "<h2 style='width: 50%; display: inline; margin-right: 35px;'>"+content.data[i][2]+"</h2>";
              tr += "<h1 style='width: 50%; display: inline; margin-right: 25px;'>"+content.data[i][4].replace(/(<([^>]+)>)|[a-z|A-Z|!|  ]/ig,'')+"<small style='color: inherit;'>Items left</small></h1>";
              tr += "</div>";
              t += tr;
            }
            if(content.data[i][4] <= 20){
              var tr = "<div class='alert alert-warning alert-dismissable'>";
              tr += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
              tr += "<h4><i class='icon fa fa-warning'></i> Warning!</h4>";
              tr += "<h2 style='width: 50%; display: inline; margin-right: 35px;'>"+content.data[i][2]+"</h2>";
              tr += "<h1 style='width: 50%; display: inline; margin-right: 25px;'>"+content.data[i][4]+"<small style='color: inherit;'>Items left</small></h1>";
              tr += "</div>";
              t += tr;
            }            
      }
      document.getElementById("productBody").innerHTML += t;      
    } 
  }

  productFetch('fetch');
   
  </script>
