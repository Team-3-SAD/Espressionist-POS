<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-12">
			<form action="" id="manage-product">
				<div class="card">
					<div class="card-header">
						   <b>Product Form</b>
				  	</div>
					<div class="card-body2">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Category <span class="asterisk">*</span></label>
								<select name="category_id" id="category_id" class="custom-select select2" required>
									<option value=""></option>
									<?php
									$qry = $conn->query("SELECT * FROM categories order by name asc");
									while($row=$qry->fetch_assoc()):
										$cname[$row['id']] = ucwords($row['name']);
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
								<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Name <span class="asterisk">*</span></label>
								<input type="text" class="form-control" name="name" required>
							</div>
							<div class="form-group">
								<label class="control-label">Description <span class="asterisk">*</span></label>
								<input name="description" id="description" cols="30" rows="4" class="form-control" required></input>
							</div>
							<div class="form-group">
								<label class="control-label">Price <span class="asterisk">*</span></label>
								<input type="number" class="form-control text-left" name="price" required>
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
								  <input type="checkbox" class="custom-control-input" id="status" name="status" checked value="1" required>
								  <label class="custom-control-label" for="status">Available</label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Product Image</label>
								<div class="custom-file" style="border: 1px solid #ced4da; border-radius: 0.25rem; padding: 0.375rem 0.75rem;">
									<input type="file" class="custom-file-input" id="product_image" name="product_image" accept="image/*" onchange="previewImage()" required>
									<label class="custom-file-label" for="product_image">Choose file</label>
								</div>
							</div>
							<!-- <div id="image_preview" class="mt-2"></div> -->
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12 text-center">
								<button class="btn btn-secondary"> Save</button>
								<button class="btn btn-default" type="button" onclick="$('#manage-product').get(0).reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p {
		margin:unset;
	}
	.custom-switch{
		cursor: pointer;
	}
	.custom-switch *{
		cursor: pointer;
	}
</style>
<script>
	//Preview Image
	function previewImage() {
    var fileInput = document.getElementById('product_image');
    var imagePreview = document.getElementById('image_preview');

    // Clear previous preview
    imagePreview.innerHTML = '';

    // Check if a file is selected
    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-fluid mt-2'; // Adjust this class as needed
            imagePreview.appendChild(img);
        }
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        // Display a message if no file is selected
        imagePreview.innerHTML = '<p>No image selected</p>';
    }
}

	$('#manage-product').on('reset',function(){
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	})
	
	$('#manage-product').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload('index.php?page=orders')
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload('index.php?page=orders')
					},1500)

				}
			}
		})
	})
	$('.edit_product').click(function(){
		start_load()
		var cat = $('#manage-product')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id')).trigger('change')
		if($(this).attr('data-status') == 1)
			$('#status').prop('checked',true)
		else
			$('#status').prop('checked',false)
		end_load()
	})
	$('.delete_product').click(function(){
		_conf("Are you sure to delete this product?","delete_product",[$(this).attr('data-id')])
	})
	function delete_product($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_product',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>
