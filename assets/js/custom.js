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

  document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.contractor-select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            const workOrderId = this.getAttribute('data-work-order-id');
            const contractorId = this.value;
            const contractorName = this.options[this.selectedIndex].getAttribute('data-contractor-name');

            // Send AJAX request to assign contractor
            fetch('services/update_work_order_issuance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `work_order_id=${workOrderId}&contractor_id=${contractorId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI: Replace dropdown with contractor name and show Issued badge
                    const td = select.parentElement;
                    td.textContent = contractorName;
                    const actionTd = td.nextElementSibling;
                    actionTd.innerHTML = '<span class="badge bg-success">Issued</span>';

                    // Show success toast
                    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                    document.getElementById('successToastMessage').textContent = 'Contractor assigned successfully!';
                    successToast.show();
                } else {
                    // Show error toast
                    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                    document.getElementById('errorToastMessage').textContent = data.message || 'Failed to assign contractor.';
                    errorToast.show();
                }
            })
            .catch(error => {
                // Show error toast
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.getElementById('errorToastMessage').textContent = 'Error: ' + error.message;
                errorToast.show();
            });
        });
    });
});


$(document).ready(function() {
  $('#mbEntriesTable').DataTable({
      responsive: true,
      pageLength: 10,
      order: [[0, 'asc']], // Sort by ID ascending by default
      language: {
          search: "Filter entries:",
          lengthMenu: "Show _MENU_ entries"
      }
  });
});