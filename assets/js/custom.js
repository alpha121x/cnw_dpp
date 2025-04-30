  $(document).ready(function () {
    let itemIndex = 1;

    // Add item dynamically
    $('#items-container').on('click', '.add-item-btn', function () {
      const newItem = `
        <div class="item-row row gy-2 gy-md-3 mb-3 align-items-end">
          <div class="col-12 col-md-2">
            <input type="text" class="form-control" name="items[${itemIndex}][number]" placeholder="Item No." required>
          </div>
          <div class="col-12 col-md-3">
            <input type="text" class="form-control" name="items[${itemIndex}][description]" placeholder="Description" required>
          </div>
          <div class="col-12 col-md-2">
            <input type="number" class="form-control" name="items[${itemIndex}][quantity]" placeholder="Quantity" min="1" required>
          </div>
          <div class="col-12 col-md-2">
            <input type="text" class="form-control" name="items[${itemIndex}][unit]" placeholder="Unit" required>
          </div>
          <div class="col-12 col-md-2">
            <input type="number" class="form-control" name="items[${itemIndex}][rate]" placeholder="Rate" step="0.01" required>
          </div>
          <div class="col-12 col-md-1 d-flex">
            <button type="button" class="btn btn-secondary btn-sm me-1 add-item-btn">+</button>
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">X</button>
          </div>
        </div>
      `;
      $('#items-container').append(newItem);
      itemIndex++;
    });

    // Remove item
    $('#items-container').on('click', '.remove-item-btn', function () {
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