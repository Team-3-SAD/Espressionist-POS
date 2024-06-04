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
                                <input type="text" class="form-control" name="name"  pattern="[a-zA-ZñÑ-]+(?:[ \-]?[a-zA-ZñÑ-]+)*" title= "Only Letters are allowed" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ \-]/g, '')" minlength="3" maxlength="30" required></input>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description <span class="asterisk">*</span></label>
                                <input name="description" id="description" class="form-control"  pattern="[a-zA-ZñÑ-]+(?:[ \-]?[a-zA-ZñÑ-]+)*" title= "Only Letters are allowed" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ \-]/g, '')" minlength="3" maxlength="30" required></input>
                            </div>
                    </div>
                            
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-secondary "> Save</button>
                                <a class="btn btn-default" type="button" onclick="$('#manage-category').get(0).reset()" href="index.php?page=categories"> Cancel</a>
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


    $('#manage-category').data('serialize', $('#manage-category').serialize()); // On load save form current state

    $(window).bind('beforeunload', function(e) {
        if ($('#manage-category').serialize() != $('#manage-category').data('serialize')) {
            var hasInput = false;
            $('#manage-category input, #manage-category textarea').each(function() {
                if ($(this).val() != '') {
                    hasInput = true;
                    return false;
                }
            });
            if (hasInput) {
                return true;
            }
        }
        e = null; // i.e; if form state change show warning box, else don't show it.
    });
</script>
