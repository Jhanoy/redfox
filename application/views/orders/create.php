

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
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
        <?php elseif($this->session->flashdata('stock_error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Low product number!</p>
            <?php echo $this->session->flashdata('stock_error'); ?>
          </div>          
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Add Order</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" id="frm" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                </div>
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Time: <?php echo date('h:i a') ?></label>
                </div>
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Issued By: <?php echo $USERNAME ?></label>
                </div>                

                <div class="col-md-4 col-xs-12 pull pull-left">
                  <div class="form-group">
                    <button type="button" class="btn btn-small btn-primary" onclick="openAddCustomer()">Add Exixting Customer</button>
                  </div>
                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Customer Name</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name" autocomplete="off" />
                      <input type="hidden" id="customer_id" name="customer_id" autocomplete="off" />
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Customer Address</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="customer_address" name="customer_address" placeholder="Enter Customer Address" autocomplete="off">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Customer Phone</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Enter Customer Phone" autocomplete="off">
                    </div>
                  </div>
                </div>
                
                
                <br /> <br/>
                <table class="table table-bordered" id="product_info_table">
                  <thead>
                    <tr>
                      <th style="width:50%">Product</th>
                      <th style="width:10%">Qty</th>
                      <th style="width:10%">Rate</th>
                      <th style="width:20%">Amount</th>
                      <th style="width:10%"><button type="button" id="add_row" class="btn btn-default"><i class="fa fa-plus"></i></button></th>
                    </tr>
                  </thead>

                   <tbody>
                     <tr id="row_1">
                       <td>
                        <select class="form-control select_group product" data-row-id="row_1" id="product_1" name="product[]" style="width:100%;" onchange="getProductData(1)" required>
                            <option value=""></option>
                            <?php foreach ($products as $k => $v): ?>
                              <option value="<?php echo $v['id'] ?>"><?php echo $v['sku'] ?></option>
                            <?php endforeach ?>
                          </select>
                        </td>
                        <td><input type="text" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal('qty',1)"></td>
                        <td>
                          <input type="text" 
                            name="rate[]" 
                            id="rate_1" 
                            class="form-control" 
                            <?php 
                              if($user_id == 1){
                                echo '';
                              }else if(!in_array('createProduct', $this->permission) && !in_array('updateProduct', $this->permission)){
                                echo 'disabled';
                              }else echo 'false' ?> 
                            required 
                            onkeyup="getTotal('rate',1)" 
                            autocomplete="off">
                          <input type="hidden" name="rate_value[]" id="rate_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td>
                          <input type="text" name="amount[]" id="amount_1" class="form-control" disabled autocomplete="off">
                          <input type="hidden" name="amount_value[]" id="amount_value_1" class="form-control" autocomplete="off">
                        </td>
                        <td><button type="button" class="btn btn-default" onclick="removeRow('1')"><i class="fa fa-close"></i></button></td>
                     </tr>
                   </tbody>
                </table>

                <br /> <br/>

                <div class="col-md-6 col-xs-12 pull pull-right">

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                    </div>
                  </div>
                  <?php if($is_service_enabled == true): ?>
                  <div class="form-group">
                    <label for="service_charge" class="col-sm-5 control-label">S-Charge <?php echo $company_data['service_charge_value'] ?> %</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="service_charge" name="service_charge" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" autocomplete="off">
                    </div>
                  </div>
                  <?php endif; ?>


<!-- //////////////////////////////////////////////////////////////////////////////////////// -->

                  <?php if($is_vat_enabled == true): ?>
                  <div class="form-group">
                    <label for="vat_charge" class="col-sm-5 control-label">Vat <?php echo $company_data['vat_charge_value'] ?> %</label>
                    <div class="col-sm-7 form-horizontal">
                      <label style="margin-left: 20px">
                      <input type="radio" id="withVat" class="checkbox-inline" name="vatSelection" onclick="subAmount()">
                        WITH VAT
                      </label>
                      <label style="margin-left: 20px">
                      <input type="radio" id="withOutVat" class="checkbox-inline" name="vatSelection" onclick="subAmount()">
                        WITHOUT VAT
                      </label>
                      <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled value="">
                      <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" value="">
                    </div>
                  </div>

<!-- /////////////////////////////////////////////////////////////////////////////////////////  -->
                  <?php endif; ?>
                  <div class="form-group">
                    <label for="discount" class="col-sm-5 control-label">Discount (%)</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount Percent" onkeyup="subAmount()" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="net_amount" class="col-sm-5 control-label">Net Amount</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off">
                      <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
                <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off">
                <button type="submit" class="btn btn-success">Create Order</button>
                <!-- <button type="button" class="btn btn-small btn-primary" onclick="getPf()">Create Proforma Invoice</button> -->
                <a href="<?php echo base_url('orders/') ?>" class="btn btn-warning">Back</a>
              </div>
              <input type="hidden" name="issuedBy" id="issuedby" value="<?php echo $USERNAME ?>" autocomplete="off">
            </form>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

          <div class="box" id="addCustomer" style="width: 750px; position: fixed; right: 0px; left: 403px; top: 75px; z-index: 1068; box-shadow: 1px 0px 20px 20px" hidden="true">
            <div class="box-header">
              <h3 class="box-title">Manage Customers</h3>
              <button class="btn-link" style="float: right; color: #000; text-decoration: none;" onclick="closeAddCustomer()"><b>X</b></button>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow: scroll; height: 475px;">
              <table id="manageTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>address</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>TIN</th>
                  <th>Action</th>
                  
                </tr>
                </thead>
                <tbody>
                  <?php if($customer_data): ?>                  
                    <?php foreach ($customer_data as $k => $v): ?>
                      <tr>
                        <td><?php echo $v['customer_info']['id']; ?></td>                        
                        <td><?php echo $v['customer_info']['fullname']; ?></td>
                        <td><?php echo $v['customer_info']['address']; ?></td>                        
                        <td><?php echo $v['customer_info']['phone']; ?></td>
                        <td><?php echo $v['customer_info']['email']; ?></td>
                        <td><?php echo $v['customer_info']['tin']; ?></td>
                        <td>
                          <button class="btn btn-primary" onclick="addCustomer('<?php echo $v['customer_info']['fullname']; ?>', '<?php echo $v['customer_info']['address']; ?>', '<?php echo $v['customer_info']['phone']; ?>', <?php echo $v['customer_info']['id']; ?>)">add</button>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>        
      </div>
      <!-- col-md-12 -->
    </div>
    <!-- /.row -->
    

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">

  
  var addCustomerWindow = document.getElementById('addCustomer');

  function closeAddCustomer(){
    addCustomerWindow.hidden = true;
  }

  function openAddCustomer(){
    addCustomerWindow.hidden = false;
  }
  
  function addCustomer(name, address, phone, id){

    var cId = document.getElementById('customer_id');
    var cName = document.getElementById('customer_name');
    var cAddress = document.getElementById('customer_address');
    var cPhone = document.getElementById('customer_phone');

    cId.value = id;
    cName.value = name;
    cAddress.value = address;
    cPhone.value = phone;
    addCustomerWindow.hidden = true;
    console.log(name+' '+address+' '+phone);

  }
  var base_url = "<?php echo base_url(); ?>";
  // Adding user permission for rate element that enable/disable the field
  var rate_disabled = "<?php 
                  if($user_id == 1){
                    echo '';
                  }else if(!in_array('createProduct', $this->permission) && !in_array('updateProduct', $this->permission)){
                    echo 'disabled';
                  }else echo ''; ?>";  
  $(document).ready(function() {
    $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#mainOrdersNav").addClass('active');
    $("#addOrderNav").addClass('active');
    manageTable = $('#manageTable').DataTable();
        
    var btnCust = '<button type="button" class="btn btn-secondary" title="Add picture tags" ' + 
        'onclick="alert(\'Call your custom code here.\')">' +
        '<i class="glyphicon glyphicon-tag"></i>' +
        '</button>'; 
  
    // Add new row in the table 
    $("#add_row").unbind('click').bind('click', function() {
      var table = $("#product_info_table");
      var count_table_tbody_tr = $("#product_info_table tbody tr").length;
      var row_id = count_table_tbody_tr + 1;
      $.ajax({
          url: base_url + '/orders/getTableProductRow/',
          type: 'post',
          dataType: 'json',
          success:function(response) {
              
              // console.log(reponse.x);
               var html = '<tr id="row_'+row_id+'">'+
                   '<td>'+ 
                    '<select class="form-control select_group product" data-row-id="'+row_id+'" id="product_'+row_id+'" name="product[]" style="width:100%;" onchange="getProductData('+row_id+')">'+
                        '<option value=""></option>';
                        $.each(response, function(index, value) {
                          html += '<option value="'+value.id+'">'+value.sku+'</option>';             
                        });
                        
                      html += '</select>'+
                    '</td>'+ 
                    '<td><input type="number" name="qty[]" id="qty_'+row_id+'" class="form-control" onkeyup="getTotal(&quot;qty&quot,'+row_id+')"></td>'+
                    '<td><input type="text" name="rate[]" id="rate_'+row_id+'" class="form-control"'+rate_disabled+' onkeyup="getTotal(&quot;rate&quot,'+row_id+')"><input type="hidden" name="rate_value[]" id="rate_value_'+row_id+'" class="form-control"></td>'+
                    '<td><input type="text" name="amount[]" id="amount_'+row_id+'" class="form-control" disabled><input type="hidden" name="amount_value[]" id="amount_value_'+row_id+'" class="form-control"></td>'+
                    '<td><button type="button" class="btn btn-default" onclick="removeRow(\''+row_id+'\')"><i class="fa fa-close"></i></button></td>'+
                    '</tr>';

                if(count_table_tbody_tr >= 1) {
                $("#product_info_table tbody tr:last").after(html);  
              }
              else {
                $("#product_info_table tbody").html(html);
              }

              $(".product").select2();

          }
        });

      return false;
    });

  }); // /document

  function getTotal(type = '', row = null) {

    function chng_qty(){
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);
    }

    function chng_rate(){
      var rate_val = Number($("#rate_"+row).val())
      $("#rate_value_"+row).val(rate_val);
      chng_qty();
    }

    if(row){
      if(type = 'rate'){
        chng_rate();
        subAmount();
      }else if(type = 'qty'){
        chng_qty();
        subAmount();
      }
    } else {
      alert('no row !! please refresh the page');
    }
  }

  // get the product information from the server
  function getProductData(row_id)
  {
    var product_id = $("#product_"+row_id).val();    
    if(product_id == "") {
      $("#rate_"+row_id).val("");
      $("#rate_value_"+row_id).val("");

      $("#qty_"+row_id).val("");           

      $("#amount_"+row_id).val("");
      $("#amount_value_"+row_id).val("");

    } else {
      $.ajax({
        url: base_url + 'orders/getProductValueById',
        type: 'post',
        data: {product_id : product_id},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
          
          $("#rate_"+row_id).val(response.price);
          $("#rate_value_"+row_id).val(response.price);

          $("#qty_"+row_id).val(1);
          $("#qty_value_"+row_id).val(1);

          var total = Number(response.price) * 1;
          total = total.toFixed(2);
          $("#amount_"+row_id).val(total);
          $("#amount_value_"+row_id).val(total);
          
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    var withVat = document.getElementById('withVat'); // FOR VAT NEW LINE -----------
    var withOutVat = document.getElementById('withOutVat'); // FOR VAT NEW LINE ----------

    var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

// ======================== ???????????? ====================================
    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);

    //

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

// =======================  VAT ON-OFF CHANGE - STARTED  =================================

  if (withVat.checked == true) {
    var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    vat = vat.toFixed(2);
    $("#vat_charge").val(vat);
    $("#vat_charge_value").val(vat);
  }else if (withOutVat.checked == true) {
    var vat = (Number($("#gross_amount").val())/100) * 0;
    vat = 0;
    $("#vat_charge").val('WITHOUT VAT');
    $("#vat_charge_value").val(0);
  }

// =======================  VAT ON-OFF CHANGE - ENDEDED  =================================
    // service
    var service = (Number($("#gross_amount").val())/100) * service_charge;
    service = service.toFixed(2);
    $("#service_charge").val(service);
    $("#service_charge_value").val(service);
    
    // total amount
    var totalAmount = (Number(totalSubAmount) + Number(vat) + Number(service));
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var discount_prc = $("#discount").val();
    if(discount_prc) {
      var discount = Number(totalAmount) * (Number(discount_prc)/100);
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);
      
    } // /else discount 

  } // /sub total amount

  function removeRow(tr_id)
  {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
  }
</script>