<?php
include('db_connect.php');

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $categories_id = $_GET['id'];

    // Fetch the category details from the database
    $result = $conn->query("SELECT * FROM categories WHERE id = $categories_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $category_name = $row['name'];
        $category_description = $row['description'];
    }
}
?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-12">
                <form action="" id="edit-category">
                    <div class="card">
                        <div class="card-header">
                            <b>Edit Category Form</b>
                        </div>
                        <div class="card-body2">
                            <input type="hidden" name="id" value="<?php echo isset($categories_id) ? $categories_id : ''; ?>">
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo isset($category_name) ? $category_name : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <input name="description" id="description" cols="30" rows="4" class="form-control" value="<?php echo isset($category_description) ? $category_description : ''; ?>"></input>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-secondary"> Save</button>
                                    <a class="btn btn-default" type="button" href="index.php?page=categories"> Back to Category Forms</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->
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
</style>
<script>
    $('#manage-category').on('reset', function() {
        $('input:hidden').val('')
    })

    $('#manage-category').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_category',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully added", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                } else if (resp == 2) {
                    alert_toast("Data successfully updated", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    })
    $('.edit_category').click(function() {
        start_load()
        var cat = $('#manage-category')
        cat.get(0).reset()
        cat.find("[name='id']").val($(this).attr('data-id'))
        cat.find("[name='name']").val($(this).attr('data-name'))
        cat.find("[name='description']").val($(this).attr('data-description'))
        end_load()
    })
    $('.delete_category').click(function() {
        _conf("Are you sure to delete this category?", "delete_category", [$(this).attr('data-id')])
    })

    function delete_category($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_category',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }
    $('table').dataTable()
</script>
