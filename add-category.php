<?php include('db_connect.php');?>

<div class="container-fluid">
    
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-12">
            <form action="" id="manage-category">
                <div class="card">
                    <div class="card-header">
                            <b>Category Form</b>
                    </div>
                    <div class="card-body2">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label class="control-label">Name <span class="asterisk">*</span></label>
                                <input type="text" class="form-control" name="name" pattern="[a-zA-ZñÑ-]+(?:[ \-]?[a-zA-ZñÑ-]+)*" title="Only Letters are allowed" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ \-]/g, ''); checkForm()" minlength="3" maxlength="30" required></input>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description <span class="asterisk">*</span></label>
                                <input name="description" id="description" class="form-control" pattern="[a-zA-ZñÑ-]+(?:[ \-]?[a-zA-ZñÑ-]+)*" title="Only Letters are allowed" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ \-]/g, ''); checkForm()" minlength="3" maxlength="30" required></input>
                            </div>
                    </div>
                            
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="save-btn" class="btn btn-secondary" disabled> Save</button>
                                <a class="btn btn-default" type="button" onclick="$('#manage-category').get(0).reset(); checkForm()" href="index.php?page=categories"> Cancel</a>
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
    td{
        vertical-align: middle !important;
    }
    td p {
        margin:unset;
    }
</style>
<script>
    function checkForm() {
        var name = document.querySelector('input[name="name"]').value;
        var description = document.querySelector('input[name="description"]').value;
        var saveBtn = document.getElementById('save-btn');
        
        if (name && description && name.length >= 3 && description.length >= 3) {
            saveBtn.disabled = false;
        } else {
            saveBtn.disabled = true;
        }
    }

    $('#manage-category').on('reset',function(){
        $('input:hidden').val('')
    })
    
    $('#manage-category').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_category',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully added",'success')
                }
                else if(resp==2){
                    alert_toast("Data successfully updated",'success')
                }
                // Temporarily unbind beforeunload event
                $(window).unbind('beforeunload');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            }
        })
    })
    $('.edit_category').click(function(){
        start_load()
        var cat = $('#manage-category')
        cat.get(0).reset()
        cat.find("[name='id']").val($(this).attr('data-id'))
        cat.find("[name='name']").val($(this).attr('data-name'))
        cat.find("[name='description']").val($(this).attr('data-description'))
        end_load()
    })
    $('.delete_category').click(function(){
        _conf("Are you sure to delete this category?","delete_category",[$(this).attr('data-id')])
    })
    function delete_category($id){
        start_load()
        $.ajax({
            url:'ajax.php?action=delete_category',
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

    var formChanged = false;
    var pendingNavigationUrl = null;

$(document).ready(function() {
    // Intercept all link clicks
    $('a').on('click', function(event) {
        if (formChanged) {
            event.preventDefault();
            pendingNavigationUrl = $(this).attr('href');
            $('#warning-modal').modal('show');
        }
    });

    // Intercept form submissions
    $('form').on('submit', function() {
        formChanged = false;
    });

    $('#manage-category input, #manage-category textarea').on('input', function() {
        formChanged = true;
    });

    $('#leave-page-btn').click(function() {
        $('#warning-modal').modal('hide');
        if (pendingNavigationUrl) {
            window.location.href = pendingNavigationUrl;
        }
    });
});


</script>