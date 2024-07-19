<?php
include 'db_connect.php';
$order = $conn->query("SELECT * FROM orders where id = {$_GET['id']}");
foreach ($order->fetch_array() as $k => $v) {
	$$k = $v;
}
$items = $conn->query("SELECT o.*,p.name FROM order_items o inner join products p on p.id = o.product_id where o.order_id = $id ");

$order_items = [];
$total_discounted_amount = 0;
while ($row = $items->fetch_assoc()) {
	$order_items[] = $row;
	$total_discounted_amount += ($row['price'] - $row['amount']); // Adjust calculation as needed
}
?>

<style>
	.flex {
		display: inline-flex;
		width: 100%;
	}

	.w-50 {
		width: 50%;
	}

	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}

	table.wborder {
		width: 100%;
		border-collapse: collapse;
	}

	table.wborder>tbody>tr,
	table.wborder>tbody>tr>td {
		border: 1px solid;
	}

	p {
		margin: unset;
	}

	.receipt-header {
		font-weight: 900;
	}
</style>
<div class="container-fluid">
	<h2 class="text-center receipt-header"><b><?php echo $amount_tendered > 0 ? "Espressionist Acknowledgement Receipt" : "Bill" ?></b></h2>
	<p>espressionist.ph@gmail.com</p>
	<p>linktr.ee/espressionist.ph</p>
	<hr>
	<p class="text-center"><b>Served by EspressoInsights</b></p>
	<h4 class="text-center"><b><?php echo $order_number ?></b></h4>
	<hr>
	<table width="100%">
		<thead>
			<tr>
				<td><b>QTY</b></td>
				<td><b>Order</b></td>
				<td class="text-right"><b>Amount</b></td>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($order_items as $row) :
			?>
				<tr>
					<td><?php echo $row['qty'] ?></td>
					<td>
						<p><?php echo $row['name'] ?></p><?php if ($row['qty'] > 0) : ?><small>(<?php echo '₱' . number_format($row['price'], 2) ?>)</small> <?php endif; ?>
					</td>
					<td class="text-right"><?php echo '₱' . number_format($row['amount'], 2) ?></td>
					<td class="text-right color"></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<hr>
	<table width="100%">
		<tbody>
			<tr>
				<td><b>Total Amount</b></td>
				<td class="text-right"><b><?php echo '₱' . number_format($total_amount, 2) ?></b></td>
			</tr>
			<?php if ($amount_tendered > 0) : ?>

				<tr>
					<td>Discounted Amount</td>
					<td class="text-right"><b><?php echo '₱' . number_format($total_discounted_amount, 2) ?></b></td>
				</tr>
				<tr>
					<td><b>Amount Tendered</b></td>
					<td class="text-right"><b><?php echo '₱' . number_format($amount_tendered, 2) ?></b></td>
				</tr>
				<tr>
					<td><b>Change</b></td>
					<td class="text-right"><b><?php echo '₱' . number_format($amount_tendered - $total_amount, 2) ?></b></td>
				</tr>

			<?php endif; ?>

		</tbody>
	</table>
	<hr>

	<div class="flex">
		<div class="w-100">
			<?php if ($amount_tendered > 0) : ?>
				<p>Invoice Number: <b><?php echo $ref_no ?></b></p>
			<?php endif; ?>
				<p><b>WIFI Password: 3xXPQ5%a</b></p>
				<p><b><?php echo date("M d, Y H:i:s", strtotime($date_created)) ?></b></p>
		</div>
	</div>
</div>
