

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Stock
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Products</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

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

        <?php if(in_array('updateProduct', $user_permission)): ?>
          <a href="<?php echo base_url('products/create') ?>" class="btn btn-primary">Add Product</a>
          <br /> <br />
        <?php endif; ?>

        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Manage Products</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="manageTable" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>No.</th>
                <th>product ID</th>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Store</th>
                <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                  <th>Action</th>
                <?php endif; ?>
              </tr>
              </thead>
              <tbody>
                  <?php if($products): ?>
                    <?php $count = 1; ?>                
                    <?php foreach ($products as $k => $v): ?>
                      <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo $v['id']; ?></td>                       
                        <td><?php echo $v['sku']; ?></td>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo $v['price']; ?></td>
                        <td><?php echo $v['qty']; ?></td>
                        <td><?php echo $v['store_id']; ?></td>
                        <td>
                          <button type="button" onclick="addStock('<?php echo $v['name']; ?>', <?php echo $v['id']; ?>)">
                            <span aria-hidden="true">&plus;</span>
                          </button>  
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php endif; ?>
                </tbody>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <div class="box" id="addCustomer" style="width: 600px; position: fixed; right: 0px; left: 403px; top: 75px; z-index: 1068; box-shadow: 1px 0px 20px 20px" hidden="true">
            <div class="box-header">
              <h3 class="box-title">Add Stock</h3>
              <button class="btn-link" style="float: right; color: #000; text-decoration: none;" onclick="closeAddStock()"><b>X</b></button>
            </div>
            <div class="box-body" style="height: 200px;">
              <form role="form" action="<?php base_url('products/addStock') ?>" method="post" class="form-horizontal">
                <div class="box-body">
                  
                  <?php echo validation_errors(); ?>              

                  <div class="col-md-12 col-xs-12 pull pull-left">
                    <div class="form-group">
                      <label id='product_name' class="col-md-12 control-label" style="text-align:left;"></label>
                      <div class="col-md-12">
                        <input type="number" class="col-md-8" id="qty" style="height: 34px; border-radius: 5px; border-color: #848d97; margin-right: 5px;" name="qty" placeholder="Enter Product Number" autocomplete="off" />
                        <input type="hidden" id="product_id" name="product_id" autocomplete="off" />
                        <button type="submit" class="btn btn-small btn-primary col-md-3" onclick="openAddStock()">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>  
              </form>              
            </div>
          </div>  
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php if(in_array('deleteProduct', $user_permission)): ?>
<!-- remove brand modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Product</h4>
      </div>

      <form role="form" action="<?php echo base_url('products/remove') ?>" method="post" id="removeForm">
        <div class="modal-body">
          <p>Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>



<script type="text/javascript">

  var addCustomerWindow = document.getElementById('addCustomer');

  function closeAddStock(){
    addCustomerWindow.hidden = true;
  }
  function openAddStock(){
    addCustomerWindow.hidden = false;
  }
  
  function addStock(name='', id){

    openAddStock();
    var name = name;
    var id = id;
    var pName = document.getElementById('product_name');
    var pId = document.getElementById('product_id');

        pName.innerHTML = name;
        pId.value = id;
  }

$(document).ready(function() {
  $('#CustomerTable').DataTable();

    $("#mainProductNav").addClass('active');
    $("#addStockNav").addClass('active');
});

</script>
