<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>

<style>
    label > span {
        color: red;
        margin-left: 5px;
    }
</style>

<div class="container-fluid">
    <div id="msg"></div>
    
    <form action="" id="manage-user">    
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
        <div class="form-group">
            <label for="name">Name <span style="color: red;">*</span></label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required maxlength="20">
        </div>
        <div class="form-group">
            <label for="username">Username <span style="color: red;">*</span></label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required maxlength="20" autocomplete="on">
        </div>
        <div class="form-group">
            <label for="password">Password <span style="color: red;">*</span></label>
            <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off" maxlength="20">
            <?php if(isset($meta['id'])): ?>
            <small><i>Leave this blank if you don't want to change the password.</i></small>
            <?php endif; ?>
        </div>
        <?php if(isset($meta['type']) && $meta['type'] == 3): ?>
            <input type="hidden" name="type" value="3">
        <?php else: ?>
        <?php if(!isset($_GET['mtype'])): ?>
        <div class="form-group">
            <label for="type">User Type</label>
            <select name="type" id="type" class="custom-select">
                <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Staff</option>
                <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
            </select>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </form>
</div>
<script>
	
	$('#manage-user').submit(function(e){
    e.preventDefault();

    // Check if the required fields are not empty
    var name = $('#name').val().trim();
    var username = $('#username').val().trim();
    var password = $('#password').val().trim();

    if (name === '' || username === '' || password === '') {
        $('#msg').html('<div class="alert alert-danger">Please fill out all required fields.</div>');
        return false; // Prevent form submission
    }

    start_load();
    $.ajax({
        url: 'ajax.php?action=save_user',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp){
            if (resp == 1) {
                alert_toast("Data successfully saved", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                $('#msg').html('<div class="alert alert-danger">Username already exists</div>');
                end_load();
            }
        }
    });
});


</script>