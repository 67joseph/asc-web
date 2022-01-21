<?php
    require __DIR__ . '/vendor/autoload.php';
    use Automattic\WooCommerce\Client;
    use Automattic\WooCommerce\HttpClient\HttpClientException;
    $woocommerce = new Client('http://test.josephadegbola.com', 
                                'ck_2dc26fe9680b2e4edde6fbb8087a707ac9f07f10', 
                                'cs_0329b96b2301eb3272858647914fd14cf11056b0',
                     [
                     'wp_api' => true, 'version' => 'wc/v3',
    ]);
    try {

    //var_dump($woocommerce);
    
    $results = $woocommerce->get('orders');
    
    $products = $woocommerce->get('products');
    
    $customers = $woocommerce->get('customers');
    
     
    $result = count($results);
    
    $customer = count($customers);
    
    $product = count($products);
    
        
    $query = ['date_min' => '2022-01-01', 'date_max' => '2023-01-20'];
    
    $sales = $woocommerce->get('reports/sales', $query);
    
    $sale = $sales[0] -> total_sales;
    
    }
    
    
    
    catch(HttpClientException $e) {
    
    $e->getMessage(); // Error message.
    
    $e->getRequest(); // Last request data.
    
    $e->getResponse(); // Last response data.
    
    }
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
    
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-1">
                    <h1 class="page-header">Dashboard</h1>
                    <div class="row placeholders">
                               <div class="col-xs-6 col-sm-3 placeholder">
                                   <p id="large">
                                       <?php echo $result?>
                                   </p>
                                   <hr>
                                   <span class="text-muted">Total Orders</span>
                               </div>
                               <div class="col-xs-6 col-sm-3 placeholder">
                                   <p id="large">
                                       <?php echo $customer?>
                                   </p>
                                   <hr>
                                   <span class="text-muted">Customers</span>
                               </div>
                               <div class="col-xs-6 col-sm-3 placeholder">
                                   <p id="large">
                                       <?php echo $product?>
                                   </p>
                                   <hr>
                                   <span class="text-muted">All Products</span>
                               </div>
                               <div class="col-xs-6 col-sm-3 placeholder">
                                   <p id="large">
                                       <?php echo $sale?>
                                   </p>
                                   <hr>
                                   <span class="text-muted">Total Sales</span>
                               </div>
                     </div>
             </div>
             
             <?php  $results = $woocommerce->get('orders'); ?>

             <div class="container">
                             <h2 class="sub-header">Orders List</h2>
                               <div class='table-responsive'>
                                   <table id='myTable' class='table table-striped table-bordered'>
                                       <thead>
                                           <tr>
                                               <th>Order #</th>
                                               <th>Customer</th>
                                               <th>Address</th>
                                               <th>Contact</th>
                                               <th>Order Date</th>
                                               <th>Status</th>
                                               <th>Actions</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                       <?php
                                       $results = json_decode(json_encode($results), true);

                                       foreach($results as $details){

                                       echo "<tr><td>" . $details["id"]."</td>
                                                 <td>" . $details["billing"]["first_name"].$details["billing"]["last_name"]."</td>
                                                 <td>" . $details["shipping"]["address_1"]."</td>
                                                 <td>" . $details["billing"]["phone"]."</td>
                                                 <td>" . $details["date_created"]."</td>
                                                 <td>" . $details["status"]."</td>
                                                 <td><a class='open-AddBookDialog btn btn-primary' data-target='#myModal' data-id=".$details['id']." data-toggle='modal'>Update</a>
                                                 <a class='open-deleteDialog btn btn-danger' data-target='#myModal1' data-id=".$details['id']." data-toggle='modal'>Delete</a></td></tr>";
                                       }?>
                                         </tbody>
                                         </table>
                               </div>
              </div>
              <div class="modal fade" id="myModal" role="dialog">
               <div class="modal-dialog">
                   <!-- Modal content-->
                   <div class="modal-content">
                       <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title">Modal Header</h4>
                       </div>
                       <div class="modal-body">
                           <p>Some text in the modal.</p>
                           <form action="" method="post">
                               <div class="form-group">
                                   <input type="text" class="form-control" name="bookId" id="bookId" value="">
                                   <label for="sel1">Select list (select one):</label>
                                   <select class="form-control" id="status" name="ostatus">
                                       <option>Pending Payment</option>
                                       <option>processing</option>
                                       <option>On Hold</option>
                                       <option>completed</option>
                                       <option>Cancelled</option>
                                       <option>Refunded</option>
                                       <option>Failed</option>
                                   </select>
                               </div>
                               <div class="modal-footer">
                                   <button type="submit" class="btn btn-block" name="btn-update">Update</button>
                               </div>
                           </form>
                       </div>
                   </div>
               </div>
            </div>
            <?php
                        if (isset($_POST['btn-update'])) {
                        $status = $_POST['bookId'];
                        $st = $_POST['ostatus'];
                        $woocommerce->put('orders/' . $status, array(
                        'status' => $st
                        ));
                        echo '<script>window.location.href = "dashboard.php";</script>';
                        //header('Location: dashboard.php');
                        }
            ?>
            <script>
                $(document).on("click", ".open-AddBookDialog", function() {
                   var myBookId = $(this).data('id');
                   $(".modal-body #bookId").val(myBookId);
               });
            </script> 
            <div class="modal fade" id="myModal1" role="dialog">
               <div class="modal-dialog">
                   <!-- Modal content-->
                   <div class="modal-content">
                       <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title">Modal Header</h4>
                       </div>
                       <div class="modal-body">
                           <p>Some text in the modal.</p>
                           <form action="" method="post">
                               <div class="form-group">
                                   <input type="text" class="form-control" name="cId" id="cId" value="">
                               </div>
                               <div class="modal-footer">
                           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                           <button type="submit" class="btn btn-danger" name="btn-delete">Delete</button>
                              </div>
                           </form>
                       </div>
                   </div>
               </div>
            </div>
            <script>
                   $(document).on("click", ".open-deleteDialog", function() {
                       var myBook = $(this).data('id');
                       $(".modal-body #cId").val(myBook);
                   });
            </script>
            <?php
            if (isset($_POST['btn-delete'])) {
                $oid = $_POST['cId'];
                $woocommerce->delete('orders/' . $oid, ['force' => true]);
                echo '<script>window.location.href = "dashboard.php";</script>';

                // header('Location: dashboard.php');
            }
            $customers = $woocommerce->get('customers');

            ?>
            <div class="container">
                                  <h2 class="sub-header">Customers List</h2>
                                  <div class='table-responsive'>
                                      <table id='myTable' class='table table-striped table-bordered'>
                                          <thead>
                                              <tr>
                                                  <th>Email</th>
                                                  <th>Name</th>
                                                  <th>Billing Address</th>
                                                  <th>Total Orders</th>
                                                  <th>Total spent</th>
                                                  <th>Avatar</th>
                                                  <th>Actions</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?php
                  foreach($customers as $customer){
                  echo "<tr><td>" . $customer["email"]."</td>
                            <td>" . $customer["first_name"].$customer["last_name"]."</td>
                            <td>" . $customer["billing"]["address_1"]."</td>
                            <td>" . $customer["orders_count"]."</td>
                            <td>" . $customer["total_spent"]."</td>
                            <td><img height='50px' width='50px' src='".$customer["avatar_url"]."'></td>
                             <td><a class='open-AddBookDialog btn btn-primary' data-target='#myModal' data-id=".$customer['id']." data-toggle='modal'>Update</a>
                             <a class='open-deleteDialog btn btn-danger' data-target='#myModal1' data-id=".$customer['id']." data-toggle='modal'>Delete</a></td></tr>";
                  }
                  ?>
                                          </tbody>
                                      </table>
                                  </div>
              </div>
                <?php
                    $products = $woocommerce->get('products');

                ?>
                <div class="container">
                                                  <h2 class="sub-header">Products List</h2>
                                                  <div class='table-responsive'>
                                                      <table id='myTable' class='table table-striped table-bordered'>
                                                          <thead>
                                                              <tr>
                                                                  <th>SKU</th>
                                                                  <th>Name</th>
                                                                  <th>Status</th>
                                                                  <th>Price</th>
                                                                  <th>Total Sales</th>
                                                                  <th>Picture</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                  <?php
                                  $products = json_decode(json_encode($products), true);
                                  foreach($products as $product){
                                  echo "<tr><td>" . $product["sku"]."</td>
                                            <td>" . $product["name"]."</td>
                                            <td>" . $product["status"]."</td>
                                            <td>" . $product["price"]."</td>
                                            <td>" . $product["total_sales"]."</td>
                                            <td><img height='50px' width='50px' src='".$product["images"][0]["src"]."'></td></tr>";
                                  }
                                  ?>
                                                          </tbody>
                                                      </table>
                                                  </div>
                  </div>
    </body>
</html>
