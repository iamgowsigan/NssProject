<?php
									
									$pendingorder=array();
									$deliverorder=array();
									$allorder=array();
									$query = mysqli_query($con, "SELECT orders.*,address.* FROM orders JOIN address ON address.a_id=orders.address_id ORDER BY o_id DESC");
									while ($row = mysqli_fetch_array($query)) {
										
										$oid=$row['o_id'];
										
										$allsentcount=0;
										$alldeliverycount=0;
										$totalordercount=0;
										
										$query2 = mysqli_query($con, "SELECT * FROM cart WHERE book_id=$oid");
										while ($row2 = mysqli_fetch_array($query2)) {
											
										
											$orderstatus=$row2['cart_status'];
											$totalordercount=$totalordercount+1;
											
											if($orderstatus=='S'){
												$allsentcount=$allsentcount+1;
											}
											if($orderstatus=='D'){
												$alldeliverycount=$alldeliverycount+1;
											}
											
										}
										
										if($totalordercount!=0 && $totalordercount==$allsentcount){
											array_push($pendingorder,$row);
											array_push($allorder,$row);
										}
										
										if($totalordercount!=0 && $totalordercount==$alldeliverycount){
											array_push($deliverorder,$row);
											array_push($allorder,$row);
										}
										
									}
									
									
									//Calculations
									//Delivered Calculations
									$coddeliverycost=0;
									$coddeliverycount=0;
									$coddeliveryproductcost=0;
									
									$paydeliverycost=0;
									$paydeliverycount=0;
									
									foreach($deliverorder as $order) {
										
										if($order['method']=='pay'){
											$paydeliverycost=$paydeliverycost+$order['delivery_cost'];
											$paydeliverycount=$paydeliverycount+1;
											}else{
											$coddeliverycost=$coddeliverycost+$order['delivery_cost'];
											$coddeliverycount=$coddeliverycount+1;
											$coddeliveryproductcost=$coddeliveryproductcost+$order['purchase_price']-$order['delivery_cost'];
											
										}	
									}
									
									//Pending/sent Calculations
									$codpendingcost=0;
									$codpendingcount=0;
									$codpendingproductcost=0;
									
									$paypendingcost=0;
									$paypendingcount=0;
									
									foreach($pendingorder as $order) {
										
										if($order['method']=='pay'){
											
											$paypendingcost=$paypendingcost+$order['delivery_cost'];
											$paypendingcount=$paypendingcount+1;
											}else{
											$codpendingcost=$codpendingcost+$order['delivery_cost'];
											$codpendingcount=$codpendingcount+1;
											$codpendingproductcost=$codpendingproductcost+$order['purchase_price']-$order['delivery_cost'];
											
										}		
									}
									
									$totalDeliveredDeliveryCharge=$paydeliverycost+$coddeliverycost;
									$totalPendingDeliveryCharge=$paypendingcost+$codpendingcost;
									
									$totalDeliveredCont=$paydeliverycount+$coddeliverycount;
									$totalPendingCont=$paypendingcount+$codpendingcount;
									
									$totalDeliveredProductCost=$coddeliveryproductcost;
									$totalPendingProductCost=$codpendingproductcost;
									

									
									
								?>