<?php  
	$countCart = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id='0'"));
	$countCart=($countCart==null)?0:$countCart;
	$countPending = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND cart_status='P'"));
	$countPending=($countPending==null)?0:$countPending;
	$countAccept = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND cart_status='A'"));
	$countAccept=($countAccept==null)?0:$countAccept;
	$countSent = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND cart_status='S'"));
	$countSent=($countSent==null)?0:$countSent;
	$countDelivery = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND cart_status='D'"));
	$countDelivery=($countDelivery==null)?0:$countDelivery;
	$countCancel = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND cart_status='C'"));
	$countCancel=($countCancel==null)?0:$countCancel;
	
	
	$countCod = mysqli_num_rows(mysqli_query($con, "SELECT * from orders WHERE method='cod'"));
	$countCod=($countCod==null)?0:$countCod;
	$countPay = mysqli_num_rows(mysqli_query($con, "SELECT * from orders WHERE method='pay'"));
	$countPay=($countPay==null)?0:$countPay;
	
	$countAdminPaid = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND admin_paid='1'"));
	$countAdminPaid=($countAdminPaid==null)?0:$countAdminPaid;
	$countAdminunpay = mysqli_num_rows(mysqli_query($con, "SELECT * from cart WHERE book_id!='0' AND admin_paid='0'"));
	$countAdminunpay=($countAdminunpay==null)?0:$countAdminunpay;
	
	
?>
<script>
	
	var colors = ["#e648f0","#fff662", "#71ff62", "#62a8ff", "#646464", "#f04848"];
	(dataColors = $("#simple-pie").data("colors")) && (colors = dataColors.split(","));
	var options = {
		chart: {
			height: 320,
			type: "pie"
		},
		series: [<?=$countCart?>,<?=$countPending?>, <?=$countAccept?>, <?=$countSent?>, <?=$countDelivery?>, <?=$countCancel?>],
		labels: ["Cart", "Pending", "Accept", "Sent", "Delivery", "Cancel"],
		colors: colors,
		legend: {
			show: !0,
			position: "bottom",
			horizontalAlign: "center",
			verticalAlign: "middle",
			floating: !1,
			fontSize: "14px",
			offsetX: 0,
			offsetY: -10
		},
		responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    height: 240
				},
                legend: {
                    show: !1
				}
			}
		}
		]
	};
	(chart = new ApexCharts(document.querySelector("#simple-pie"), options)).render();
	colors = ["#39afd1", "#ffbc00"];
	(dataColors = $("#simple-donut").data("colors")) && (colors = dataColors.split(","));
	options = {
		chart: {
			height: 320,
			type: "donut"
		},
		series: [<?=$countCod?>,<?=$countPay?>],
		legend: {
			show: !0,
			position: "bottom",
			horizontalAlign: "center",
			verticalAlign: "middle",
			floating: !1,
			fontSize: "14px",
			offsetX: 0,
			offsetY: -10
		},
		labels: ["COD Order Count", "Paid Order Count"],
		colors: colors,
		responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    height: 240
				},
                legend: {
                    show: !1
				}
			}
		}
		]
	};
	(chart = new ApexCharts(document.querySelector("#simple-donut"), options)).render();
	
	colors = ["#39afd1", "#ffbc00"];
	(dataColors = $("#simple-donut2").data("colors")) && (colors = dataColors.split(","));
	options = {
		chart: {
			height: 320,
			type: "donut"
		},
		series: [<?=$countAdminPaid?>,<?=$countAdminunpay?>],
		legend: {
			show: !0,
			position: "bottom",
			horizontalAlign: "center",
			verticalAlign: "middle",
			floating: !1,
			fontSize: "14px",
			offsetX: 0,
			offsetY: -10
		},
		labels: ["Paid to Store", "Not Paid"],
		colors: colors,
		responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    height: 240
				},
                legend: {
                    show: !1
				}
			}
		}
		]
	};
	(chart = new ApexCharts(document.querySelector("#simple-donut2"), options)).render();
	
</script>