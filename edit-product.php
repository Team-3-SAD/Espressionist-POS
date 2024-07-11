<?php
include('db_connect.php');

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
	$products_id = $_GET['id'];

	// Fetch the product details from the database
	$result = $conn->query("SELECT * FROM products WHERE id = $products_id");
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$product_category_id = $row['category_id'];
		$product_name = $row['name'];
		$product_description = $row['description'];
		$product_price = $row['price'];
		$product_status = $row['status'];
		$status = isset($_POST['status']) ? 1 : 0;
	}
}
?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<form action="" id="edit-product"> <!-- Ensure the form ID is correct -->
					<div class="card">
						<div class="card-header">
							<b>Edit Product Form</b>
						</div>
						<div class="card-body2">
							<input type="hidden" name="id" value="<?php echo isset($products_id) ? $products_id : ''; ?>">
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category_id" id="category_id" class="custom-select select2" required>
									<option value=""></option>
									<?php
									$qry = $conn->query("SELECT * FROM categories order by name asc");
									while ($row = $qry->fetch_assoc()) :
										$cname[$row['id']] = ucwords($row['name']);
										$selected = ($row['id'] == $product_category_id) ? 'selected' : '';
									?>
										<option value="<?php echo $row['id'] ?>" <?php echo $selected ?>><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control" name="name" value="<?php echo isset($product_name) ? $product_name : ''; ?>" required>
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<input name="description" id="description" cols="30" rows="4" class="form-control" value="<?php echo isset($product_description) ? $product_description : ''; ?>" required></input>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-left" name="price" value="<?php echo isset($product_price) ? $product_price : ''; ?>" required>
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="status" name="status" <?php echo isset($product_status) && $product_status == 1 ? 'checked' : ''; ?> value="1">
									<label class="custom-control-label" for="status">Available</label>
								</div>
							</div>

						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12 text-left">
									<button class="btn btn-success"> Save</button>
									<a class="btn btn-secondary" type="button" href="index.php?page=products"> Back</a>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset;
	}

	.custom-switch {
		cursor: pointer;
	}

	.custom-switch * {
		cursor: pointer;
	}
</style>
<script>
	$('#edit-product').on('reset', function() {
		$('input:hidden').val('');
		$('.select2').val('').trigger('change');
	});

	$('#edit-product').submit(function(e) {
		e.preventDefault();
		start_load();
		if (!$('#status').is(':checked')) {
			$('#status').prop('checked', false).val('0');
		}
		$.ajax({
			url: 'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully added", 'success');
					setTimeout(function() {
						window.location.href = "index.php?page=products";
					}, 1500);
				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success');
					setTimeout(function() {
						window.location.href = "index.php?page=products";
					}, 1500);
				}
			}
		});
	});

	$('.edit_product').click(function() {
		start_load();
		var cat = $('#edit-product'); // Changed to #edit-product
		cat.get(0).reset();
		cat.find("[name='id']").val($(this).attr('data-id'));
		cat.find("[name='name']").val($(this).attr('data-name'));
		cat.find("[name='description']").val($(this).attr('data-description'));
		cat.find("[name='price']").val($(this).attr('data-price'));
		cat.find("[name='category_id']").val($(this).attr('data-category_id')).trigger('change');
		if ($(this).attr('data-status') == 1)
			$('#status').prop('checked', true);
		else
			$('#status').prop('checked', false);
		end_load();
	});

	$('.delete_product').click(function() {
		_conf("Are you sure to delete this product?", "delete_product", [$(this).attr('data-id')]);
	});

	function delete_product($id) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_product',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				}
			}
		});
	}

	$('table').dataTable();
</script>