
<?php
$orderId = $_GET['id'];
?>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Your Cash Token Invoice</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>

	<body>

	<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
				
			}
			.loader,
            .loader:after {
                border-radius: 50%;
                width: 10em;
                height: 10em;
            }
            .loader {            
                margin: 60px auto;
                font-size: 10px;
                position: relative;
                text-indent: -9999em;
                border-top: 1.1em solid rgba(255, 255, 255, 0.2);
                border-right: 1.1em solid rgba(255, 255, 255, 0.2);
                border-bottom: 1.1em solid rgba(255, 255, 255, 0.2);
                border-left: 1.1em solid #ffffff;
                -webkit-transform: translateZ(0);
                -ms-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-animation: load8 1.1s infinite linear;
                animation: load8 1.1s infinite linear;
            }
            @-webkit-keyframes load8 {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
            @keyframes load8 {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
            #loadingDiv {
                position:absolute;;
                top:0;
                left:0;
                width:100%;
                height:100%;
                background-color:#54eba0;
            }
		</style>	 
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
        
        $order = $woocommerce->get('orders/'.$orderId);
        $order = json_decode(json_encode($order), true);
        $details = $order;
        ?>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title"> 

									<img src="https://ng.cashtoken.africa/assets/images/logo.png" style="width: 100%; max-width: 100px; height: 100px;" />
								</td>

								<td>
									Invoice #:<?php echo $details['number']; ?><br />
									Paid: <?php echo $details['date_created']; ?><br />
									 
								</td>
							</tr>
						</table>
					</td>
				</tr>
                 
				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									<?php echo $details['billing']['address_1']."<br>".$details['billing']['address_2']."<br />
										". $details['billing']['city'].",".$details['billing']['state'].",".$details['billing']['postcode']."<br>".$details['billing']['country'];
									?>
								</td>

								<td>
									<?php echo $details['billing']['first_name']." ".$details['billing']['last_name']."<br>".$details['billing']['phone']."<br />".$details['billing']['email'];
									?> 
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Payment Method</td>

					<td><?php echo $details['payment_method']; ?> #</td>
				</tr>

				<tr class="details">
					<td><?php echo $details['payment_method_title']; ?> #</td>

					<td><?php echo $details['transaction_id']; ?> #</td>
				</tr>
                
				
				<tr class="heading">
					<td>Item(s)</td>
                                                
					<td>Price</td>
				</tr>
                <?php
                foreach($details['line_items'] as $items){
                $orderTotal = $details["total"];
                $reward = $orderTotal * 0.05;
                $reward = $details['currency_symbol']."".$reward;
                echo"<tr  class='item'>
                    <td>" . $items["name"]."</td>
            
					<td>" . $details['currency_symbol']."".$items["price"]."</td>
			    	</tr>";
                
                 } ?>
				<tr class="heading">
					<td>Order Instructions</td>
                                                
					<td>-</td>
				</tr>
				<tr class='item'>
				    <td>Your order will be delivered to your shipping address very soon. Note: Delivery takes up to 7 days to complete arrive.</td>
				</tr>
				<tr class="heading">
					<td>Reward Summary</td>
                                                
					<td>-</td>
				</tr>
				<tr class='item'>
				    <td>Your CELD Wallet has been credited with <?php echo $reward; ?>. That's 5% of your purchase!!! </td>
				</tr>
			</table>
		</div>
			<script>
		    $('body').append('<div style="" id="loadingDiv"><center><h3 style="color:white; font-family:nyala;">Your invoice is being processed...</h3></center><div class="loader">Loading...</div></div>');
            $(window).on('load', function(){
              setTimeout(removeLoader, 3000); //wait for page load PLUS two seconds.
            });
            function removeLoader(){
                $( "#loadingDiv" ).fadeOut(500, function() {
              // fadeOut complete. Remove the loading div
              $( "#loadingDiv" ).remove(); //makes page more lightweight 
            });  
            }
		</script>
          <?php 
        //   $order = $woocommerce->get('orders/'.$orderId);
          
        //   echo "<pre>";
        //   print_r($order);
        //   echo "</pre>";
          ?>
	</body>
</html>
