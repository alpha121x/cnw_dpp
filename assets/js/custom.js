$(document).ready(function() {
    let itemIndex = 1;

    // Add new item row
    $('#add-item-btn').on('click', function() {
      const newItemRow = `
        <div class="item-row row gy-2 gy-md-3 mb-3 align-items-end">
          <div class="col-12 col-md-4">
            <label for="item_name_${itemIndex}" class="form-label">Item Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="items[${itemIndex}][name]" id="item_name_${itemIndex}" placeholder="Enter item name" required>
          </div>
          <div class="col-12 col-md-3">
            <label for="item_quantity_${itemIndex}" class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="items[${itemIndex}][quantity]" id="item_quantity_${itemIndex}" placeholder="Enter quantity" min="1" required>
          </div>
          <div class="col-12 col-md-3">
            <label for="item_rate_${itemIndex}" class="form-label">Rate Quoted <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="items[${itemIndex}][rate]" id="item_rate_${itemIndex}" placeholder="Enter rate" step="0.01" required>
          </div>
          <div class="col-12 col-md-2">
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">Remove</button>
          </div>
        </div>`;
      $('#items-container').append(newItemRow);
      itemIndex++;
    });

    // Remove item row
    $('#items-container').on('click', '.remove-item-btn', function() {
      if ($('.item-row').length > 1) {
        $(this).closest('.item-row').remove();
      } else {
        alert('At least one item is required.');
      }
    });
  });

  $(document).ready(function() {
    $('#workOrderTable').DataTable({
      "responsive": true,
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "columnDefs": [
        { "orderable": false, "targets": [3, 4] } // Disable sorting on Contractor and Action columns
      ]
    });

    // Handle contractor dropdown change
    $('#workOrderTable').on('change', '.contractor-select', function() {
      var workOrderId = $(this).data('work-order-id');
      var contractorId = $(this).val();

      if (!contractorId) {
        alert('Please select a valid contractor.');
        return;
      }

      $.ajax({
        url: 'services/update-work-order-issuance.php',
        type: 'POST',
        data: {
          work_order_id: workOrderId,
          contractor_id: contractorId
        },
        success: function(response) {
          var result = JSON.parse(response);
          if (result.success) {
            alert('Work order WO-' + ('000' + workOrderId).slice(-3) + ' assigned to contractor successfully.');
          } else {
            alert('Error: ' + result.error);
          }
        },
        error: function() {
          alert('Failed to update work order issuance.');
        }
      });
    });
  });