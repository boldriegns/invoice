<?php
include('header.php');
include('functions.php');
?>

<h1>Order List</h1>
<hr>

<div class="row">
    <div class="col-xs-12">
        <div id="response" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Order Information</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <?php getOrders(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for delete confirmation -->
<div id="confirmDeleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete Order</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script>
$(document).on('click', ".delete-order", function(e) {
    e.preventDefault();

    var productId = $(this).data('product_id');

    // Set data-product_id for confirmation modal
    $('#confirmDeleteBtn').data('product_id', productId);

    // Show confirmation modal
    $('#confirmDeleteModal').modal('show');
});

$(document).on('click', "#confirmDeleteBtn", function(e) {
    e.preventDefault();

    var productId = $(this).data('product_id');

    $.ajax({
        url: 'response.php',
        type: 'POST',
        data: { 
            action: 'delete_order', // Specify the action for deleting a product
            product_id: productId // Sending productId as POST data
        },
        dataType: 'json',
        success: function(data) {
            // Handle success response
            $('#response .message').html('<strong>' + data.status + '</strong>: ' + data.message);
            $('#response').removeClass('alert-warning').addClass('alert-success').fadeIn();
            $('html, body').animate({ scrollTop: $('#response').offset().top }, 1000);
            // Remove deleted product from UI
            $('[data-product_id="' + productId + '"]').closest('tr').remove();
        },
        error: function(xhr, status, error) {
            // Handle error response
            $('#response .message').html('<strong>Error</strong>: Could not delete the product.');
            $('#response').removeClass('alert-success').addClass('alert-warning').fadeIn();
            $('html, body').animate({ scrollTop: $('#response').offset().top }, 1000);
        }
    });

    // Hide confirmation modal
    $('#confirmDeleteModal').modal('hide');
});

</script>
