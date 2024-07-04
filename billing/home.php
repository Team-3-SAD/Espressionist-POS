<?php include '../db_connect.php' ?>
<style>
    span.float-right.summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        top: 0;
    }

    .bg-gradient-primary {
        background: rgb(119, 172, 233);
        background: linear-gradient(149deg, rgba(119, 172, 233, 1) 5%, rgba(83, 163, 255, 1) 10%, rgba(46, 51, 227, 1) 41%, rgba(40, 51, 218, 1) 61%, rgba(75, 158, 255, 1) 93%, rgba(124, 172, 227, 1) 98%);
    }

    .btn-primary-gradient {
        background: linear-gradient(to right, #1e85ff 0%, #00a5fa 80%, #00e2fa 100%);
    }

    .btn-danger-gradient {
        background: linear-gradient(to right, #f25858 7%, #ff7840 50%, #ff5140 105%);
    }

    main .card {
        height: calc(100%);
    }

    main .card-body {
        height: calc(100%);
        overflow: auto;
        padding: 5px;
        position: relative;
    }

    main .card-footer {
        background-color: none;
    }

    main .container-fluid,
    main .container-fluid>.row,
    main .container-fluid>.row>div {
        /*height:calc(100%);*/
    }

    #o-list {
        height: calc(87%);
        overflow: auto;
    }

    #calc {
        position: absolute;
        bottom: 1rem;
        height: calc(10%);
        width: calc(98%);
    }

    .prod-item {
        min-height: 12vh;
        cursor: pointer;
    }

    .prod-item:hover {
        opacity: .8;
    }

    .prod-item .card-body {
        display: flex;
        justify-content: center;
        align-items: center;

    }

    input[name="qty[]"] {
        width: 30px;
        text-align: center
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #cat-list {
        /*height: calc(100%)*/
    }

    .cat-item {
        cursor: pointer;
    }

    .cat-item:hover {
        opacity: .8;
    }

    .discount {
        border-radius: 4px;
    }

    .discounted {
        background-color: #d4edda;
    }

    .discount-badge {
        display: inline-block;
        padding: 2px 6px;
        font-size: 12px;
        font-weight: bold;
        color: #fff;
        background-color: #28a745;
        border-radius: 12px;
        margin-left: 10px;
    }

    .original-price {
        text-decoration: line-through;
        color: red;
        margin-right: 5px;
    }
</style>

<?php
if (isset($_GET['id'])) :
    $order = $conn->query("SELECT * FROM orders where id = {$_GET['id']}");
    foreach ($order->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $items = $conn->query("SELECT o.*,p.name FROM order_items o inner join products p on p.id = o.product_id where o.order_id = $id ");
endif;
?>
<div class="container-fluid o-field">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-8 col-md-12 p-field">
            <div class="card">
                <div class="card-header text-dark">
                    <h5>Products</h5>
                </div>
                <div class="card-body row" id='prod-list'>
                    <div class="col-md-12">
                        <div class="row mx-auto" id="cat-list">
                            <div class="mt-3 mx-auto cat-item" data-id='all'>
                                <button class="btn btn-secondary"><b class="text-white">All</b></button>
                            </div>
                            <?php
                            $qry = $conn->query("SELECT * FROM categories order by name asc");
                            while ($row = $qry->fetch_assoc()) :
                            ?>
                                <div class="mt-3 mx-auto cat-item" data-id='<?php echo $row['id'] ?>'>
                                    <button class="btn btn-secondary"><?php echo ucwords($row['name']) ?></button>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <hr>
                        <div class="row">
                            <?php
                            $prod = $conn->query("SELECT * FROM products where status = 1 order by name asc");
                            if ($prod->num_rows <= 0) {
                            ?>
                                <div class="col-md-12 text-center mt-5">
                                    <h5>No products available</h5>
                                    <a href="#" class="btn btn-primary" id="add_product_btn">Add Product</a>
                                </div>
                                <?php
                            } else {
                                while ($row = $prod->fetch_assoc()) :
                                ?>
                                    <div class="col-lg-2 col-md-4 col-6 mb-2">
                                        <div class="prod-item text-center mx-auto" data-json='<?php echo json_encode($row) ?>' data-category-id="<?php echo $row['category_id'] ?>">
                                            <img src="../assets/uploads/espression.jpg" class="rounded img-fluid" alt="<?php echo $row['name'] ?>">
                                            <span class="mx-auto">
                                                <?php echo $row['name'] ?>
                                            </span>
                                        </div>
                                    </div>
                            <?php endwhile;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-center">
                        <button class="btn btn-sm col-md-4 col-sm-5 col-6 btn-secondary mr-2" type="button" id="pay">Pay</button>
                        <!-- <button class="btn btn-sm col-md-4 col-sm-5 col-6 btn-secondary" type="button" id="save_order">Pay later</button> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6><b>Order List</b></h6>
                        <a class="btn btn-secondary btn-sm" href="../index.php">
                            <i class="fa fa-home"></i> Home
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" id="manage-order">
                        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                        <div class="receipt" id='o-list'>
                            <div class="d-flex w-100 mb-2">
                                <label for="order_number" class="text-dark"><b>Order Number: </b></label>
                                <input type="number" id="order_number" class="form-control-sm ml-2" name="order_number" pattern="^\d{1,2}$" max="99" value="<?php echo isset($order_number) ? $order_number : '' ?>" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2);">
                            </div>

                            <table class="table mb-5">
                                <colgroup>
                                    <col width="20%">
                                    <col width="40%">
                                    <col width="40%">
                                    <col width="5%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>QTY</th>
                                        <th>Order</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($items)) :
                                        while ($row = $items->fetch_assoc()) :
                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class=" btn-minus"><b> </b></span>
                                                        <input type="number" name="qty[]" class="form-control-sm" value="<?php echo $row['qty'] ?>">
                                                        <span class="btn-plus"><b></b></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="item_id[]" value="<?php echo $row['id'] ?>">
                                                    <input type="hidden" name="product_id[]" value="<?php echo $row['product_id'] ?>"><?php echo ucwords($row['name']) ?>
                                                    <small class="psmall"> (<?php echo number_format($row['price'], 2) ?>)</small>
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" name="price[]" value="<?php echo $row['price'] ?>">
                                                    <input type="hidden" name="amount[]" value="<?php echo $row['amount'] ?>">
                                                    <span class="amount"><?php echo number_format($row['amount'], 2) ?></span>
                                                    <span class="discounted-amount" style="color: green;"></span>
                                                </td>
                                                <td>
                                                    <span class="delete"><b><i class="fa fa-trash-alt"></i></b></span>
                                                </td>
                                                <input type="hidden" id="formModified" value="0">
                                            </tr>
                                            <script>
                                                $(document).ready(function() {
                                                    qty_func()
                                                    calc()
                                                    cat_func();
                                                })
                                            </script>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-block" id="calc">
                            <table class="table" width="100%">
                                <tbody>
                                    <tr>
                                        <td><b>
                                                <h6>Total</h6>
                                            </b></td>
                                        <td class="text-right">
                                            <input type="hidden" name="total_amount" value="0">
                                            <input type="hidden" name="total_tendered" value="0">
                                            <span class="mt-5">
                                                <h6><b id="total_amount">0.00</b></h6>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Pay Modal -->
<div class="modal fade" id="pay_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Pay</b></h5>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="">Amount Payable</label>
                        <input type="number" class="form-control text-right" id="apayable" readonly="" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Amount Tendered</label>
                        <input type="text" class="form-control text-right" id="tendered" value="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="">Change</label>
                        <input type="text" class="form-control text-right" id="change" value="0.00" readonly="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary btn-sm" form="manage-order">Pay</button>
                <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Delete Product</b></h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="confirm_delete">Delete</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<!-- Discount Modal -->
<div class="modal fade" id="discount_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Apply Discount</b></h5>
            </div>
            <div class="modal-body">
                <p>Enter discount code to apply:</p>
                <input type="text" id="discount_code" class="form-control" placeholder="Enter discount code">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" id="proceed_discount">Proceed</button>
                <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Discard Modal -->
<div class="modal fade" id="discard_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Discard Transaction</b></h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to discard the transaction?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" id="confirm_discard">Discard</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    var total;
    var selectedProduct;
    cat_func();

    $('#prod-list .prod-item').click(function() {
        var data = $(this).attr('data-json');
        data = JSON.parse(data);
        if ($('#o-list tr[data-id="' + data.id + '"]').length > 0) {
            var tr = $('#o-list tr[data-id="' + data.id + '"]');
            var qty = tr.find('[name="qty[]"]').val();
            qty = parseInt(qty) + 1;
            qty = tr.find('[name="qty[]"]').val(qty).trigger('change');
            calc();
            return false;
        }
        var tr = $('<tr class="o-item"></tr>');
        tr.attr('data-id', data.id);
        tr.append('<td><div class="d-flex align-items-center"><span class="btn-minus"><b></i></b></span><input type="number" name="qty[]" id="" value="1"><span class="btn-plus"><b></b></span></div></td>');

        tr.append('<td><input type="hidden" name="item_id[]" id=""><input type="hidden" name="product_id[]" id="" value="' + data.id + '">' + data.name + ' <small class="psmall">(' + (parseFloat(data.price).toLocaleString("en-US", {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })) + ')</small></td>');

        tr.append('<td class="text-right"><input type="hidden" name="price[]" id="" value="' + data.price + '"><input type="hidden" name="amount[]" id="" value="' + data.price + '"><span class="amount">' + (parseFloat(data.price).toLocaleString("en-US", {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })) + '</span></td>');

        tr.append('<td><span class="delete"><b><i class="fa fa-trash-alt text"></i></b></span></td>');
        $('#o-list tbody').append(tr);
        qty_func();
        calc();
        cat_func();
    });

    function qty_func() {
        $('#o-list .btn-minus').click(function() {
            var qty = $(this).siblings('input').val();
            qty = qty > 1 ? parseInt(qty) - 1 : 1;
            $(this).siblings('input').val(qty).trigger('change');
            calc();
        });
        $('#o-list .btn-plus').click(function() {
            var qty = $(this).siblings('input').val();
            qty = parseInt(qty) + 1;
            $(this).siblings('input').val(qty).trigger('change');
            calc();
        });
        $('#o-list .btn-rem').click(function() {
            $(this).closest('tr').remove();
            calc();
        });
    }

    function calc() {
        // Attach change event listener to quantity inputs
        $('[name="qty[]"]').off('change').on('change', function() {
            var tr = $(this).closest('tr');
            var qty = parseFloat($(this).val());

            // Validate qty to be between 1 and 10
            if (qty < 1 || qty > 10 || isNaN(qty)) {
                alert_toast('Quantity must be between 1 and 10.', 'danger');
                $(this).val(1); // Set default value to 1 if out of range
                qty = 1;
            }

            var price = parseFloat(tr.find('[name="price[]"]').val());
            var amount = qty * price;

            tr.find('[name="amount[]"]').val(amount);
            tr.find('.amount').text(amount.toLocaleString("en-US", {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            if (tr.find('.discounted-amount').length) {
                var discount = tr.find('.discounted-amount').data('discount');
                var discountedAmount = amount - discount;
                tr.find('.discounted-amount').text('(' + discountedAmount.toLocaleString("en-US", {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ')');
                amount = discountedAmount;
            }
            tr.find('[name="amount[]"]').val(amount); // Ensure discounted amount is stored
            updateTotal();
        });

        // Update the total amount
        function updateTotal() {
            var total = 0;
            $('[name="amount[]"]').each(function() {
                total += parseFloat($(this).val());
            });

            $('[name="total_amount"]').val(total);
            $('#total_amount').text(total.toLocaleString("en-US", {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        // Initial calculation to set initial values
        $('[name="qty[]"]').trigger('change');
    }

    // Call calc() to ensure initial setup
    $(document).ready(function() {
        calc();
    });


    function cat_func() {
        $('.cat-item').click(function() {
            var id = $(this).attr('data-id');
            if (id == 'all') {
                $('.prod-item').parent().toggle(true);
            } else {
                $('.prod-item').each(function() {
                    if ($(this).attr('data-category-id') == id) {
                        $(this).parent().toggle(true);
                    } else {
                        $(this).parent().toggle(false);
                    }
                });
            }
        });
    }

    $('#save_order').click(function() {
        $('#tendered').val('').trigger('change');
        $('[name="total_tendered"]').val('');
        $('#manage-order').submit();
    });

    $("#pay").click(function() {
        start_load();

        var amount = $('[name="total_amount"]').val();
        if ($('#o-list tbody tr').length <= 0) {
            alert_toast("Please add at least 1 product first.", 'danger');
            end_load();
            return false;
        }

        // Check if order number is empty
        var orderNumber = $('[name="order_number"]').val().trim();
        if (orderNumber === '') {
            alert_toast("Please enter an order number.", 'danger');
            end_load();
            return false;
        }

        $('#apayable').val(parseFloat(amount).toLocaleString("en-US", {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));

        $('#pay_modal').modal('show');

        setTimeout(function() {
            $('#tendered').val('').trigger('change');
            $('#tendered').focus();
            end_load();
        }, 500);
    });

    $('#tendered').keyup('input', function(e) {
        if (e.which == 13) {
            $('#manage-order').submit();
            return false;
        }
        var tend = $(this).val();
        tend = tend.replace(/,/g, '');
        $('[name="total_tendered"]').val(tend);
        if (tend == '')
            $(this).val('');
        else
            $(this).val((parseFloat(tend).toLocaleString("en-US")));
        tend = tend > 0 ? tend : 0;
        var amount = $('[name="total_amount"]').val();
        var change = parseFloat(tend) - parseFloat(amount);
        $('#change').val(parseFloat(change).toLocaleString("en-US", {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    });

    $('#tendered').on('input', function() {
        var val = $(this).val();
        val = val.replace(/[^0-9 \,]/, '');
        $(this).val(val);
    });

    $('#manage-order').submit(function(e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: '../ajax.php?action=save_order',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp > 0) {
                    if ($('[name="total_tendered"]').val() > $('[name="total_amount"]').val()) {
                        alert_toast("Order placed.", 'success');
                        setTimeout(function() {
                            var nw = window.open('../receipt.php?id=' + resp, "_blank", "width=900,height=600");
                            setTimeout(function() {
                                nw.print();
                                setTimeout(function() {
                                    nw.close();
                                    location.reload();
                                }, 500);
                            }, 500);
                        }, 500);
                    } else {
                        alert_toast("Invalid Tendered Amount.", 'danger');
                        setTimeout(function() {}, 500);
                    }
                }
            }
        });
    });

    $('.prod-item').hover(function() {
        $(this).addClass('hovered');
    }, function() {
        $(this).removeClass('hovered');
    });

    $(document).on('click', '.o-item td:nth-child(2)', function() {
        selectedProduct = $(this).closest('.o-item');
        $('#discount_modal').modal('show');
    });

    $('#proceed_discount').click(function() {
        var code = $('#discount_code').val();
        if (code === 'EMPLOYEE_20' || code === 'CUSTOMER_20') {
            var amountElement = selectedProduct.find('.amount');
            var amount = parseFloat(amountElement.text().replace(/,/g, ''));
            var qty = parseInt(selectedProduct.find('[name="qty[]"]').val());
            alert_toast('Discount applied!', 'success');

            if (qty !== 1) {
                alert_toast('Discount can only be applied to products with a quantity of 1.', 'danger');
                return;
            }

            if (selectedProduct.hasClass('discount-active')) {
                alert_toast('Discount has already been applied to this product.', 'danger');
                return;
            }

            var discount = 0.20 * amount;
            var discountedAmount = amount - discount;

            if (!selectedProduct.find('.discounted-amount').length) {
                selectedProduct.find('td').eq(2).append('<span class="discounted-amount" data-discount="' + discount + '" style="color: green;">(' + discountedAmount.toLocaleString("en-US", {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ')</span>');
            } else {
                selectedProduct.find('.discounted-amount').text('(' + discountedAmount.toLocaleString("en-US", {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ')').css('color', 'green');
                selectedProduct.find('.discounted-amount').data('discount', discount);
            }

            amountElement.text(discountedAmount.toLocaleString("en-US", {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            selectedProduct.find('[name="amount[]"]').val(discountedAmount);
            selectedProduct.addClass('discount-active');
            calc();
            $('#discount_modal').modal('hide');
        } else {
            alert_toast('Incorrect discount code.', 'danger');
        }
        discountApplied = true;
        $('#discount_modal').modal('hide');
    });

    $('#discount_modal').on('hidden.bs.modal', function() {
        $('#discount_code').val('');
    });

    $(document).on('click', '.delete', function() {
        var tr = $(this).closest('tr');
        $('#delete_modal').modal('show');
        $('#confirm_delete').off('click').on('click', function() {
            tr.remove();
            calc();
            $('#delete_modal').modal('hide');
        });
    });

    $('#discount_modal').on('hidden.bs.modal', function() {
        $('#discount_code').val('');
    });

    $('.discount').click(function() {
        if (!discountApplied) {
            $('#discount_modal').modal('show');
        } else {
            alert_toast('1 discount per transaction only');
        }
    });

    // Event handler for beforeunload
    function beforeUnloadHandler(e) {
        if ($('#formModified').val() == '1' && $('#o-list tbody tr').length > 0) {
            var confirmationMessage = 'You have unsaved changes. Are you sure you want to leave this page?';
            console.log(confirmationMessage); // Log confirmation message
            (e || window.event).returnValue = confirmationMessage;
            return confirmationMessage;
        }
    }

    // Add the beforeunload event listener
    $(window).on('beforeunload', beforeUnloadHandler);

    // Event listener for clicking on links and buttons
    $(document).on('click', 'a[href^="../"], a[href^="/"], a:not([href]), button[type="submit"]', function(e) {
        if ($('#formModified').val() == '1' && $('#o-list tbody tr').length > 0) {
            e.preventDefault();
            showDiscardModal();
            return false;
        }
    });

    // Function to show the discard modal
    function showDiscardModal() {
        console.log('Showing discard modal'); // Log when discard modal is shown
        $('#discard_modal').modal('show');
    }

    // Function to hide the discard modal
    function hideDiscardModal() {
        console.log('Hiding discard modal'); // Log when discard modal is hidden
        $('#discard_modal').modal('hide');
    }

    // Event listener for confirming discard action
    $('#confirm_discard').click(function() {
        console.log('Confirmed discard action'); // Log when discard action is confirmed
        hideDiscardModal();
        $('#formModified').val('0'); // Reset formModified flag
        // Optionally, redirect the user after discarding changes
        window.location.href = '../index.php'; // Redirect to desired URL
    });

    // Event listener for canceling discard action
    $('#discard_modal').on('hidden.bs.modal', function() {
        console.log('Discard modal hidden'); // Log when discard modal is hidden
        $(window).on('beforeunload', beforeUnloadHandler); // Re-add the beforeunload event listener
    });
</script>
