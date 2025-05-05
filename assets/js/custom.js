$(document).ready(function () {
  let itemIndex = 1;

  // Add new item row
  $("#items-container").on("click", ".add-item-btn", function () {
    const newRow = `
    <div class="item-row row gy-2 gy-md-3 mb-3 align-items-end">
      <div class="col-12 col-md-2">
        <input type="text" class="form-control" name="items[${itemIndex}][name]" placeholder="Enter item name" required>
      </div>
      <div class="col-12 col-md-3">
        <input type="text" class="form-control" name="items[${itemIndex}][description]" placeholder="Enter description">
      </div>
      <div class="col-12 col-md-2">
        <input type="number" class="form-control" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" required>
      </div>
      <div class="col-12 col-md-2">
        <input type="text" class="form-control" name="items[${itemIndex}][unit]" placeholder="Unit" required>
      </div>
      <div class="col-12 col-md-2">
        <input type="number" class="form-control" name="items[${itemIndex}][rate]" step="0.01" placeholder="Rate" required>
      </div>
      <div class="col-12 col-md-1 d-grid">
        <button type="button" class="btn btn-danger btn-sm remove-item-btn">X</button>
      </div>
    </div>`;
    $("#items-container").append(newRow);
    itemIndex++;
  });

  // Remove item row
  $("#items-container").on("click", ".remove-item-btn", function () {
    if ($(".item-row").length > 1) {
      $(this).closest(".item-row").remove();
    } else {
      alert("At least one item is required.");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const selects = document.querySelectorAll(".contractor-select");
  selects.forEach((select) => {
    select.addEventListener("change", function () {
      const workOrderId = this.getAttribute("data-work-order-id");
      const contractorId = this.value;
      const contractorName = this.options[this.selectedIndex].getAttribute(
        "data-contractor-name"
      );

      // Send AJAX request to assign contractor
      fetch("services/update_work_order_issuance.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `work_order_id=${workOrderId}&contractor_id=${contractorId}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Update UI: Replace dropdown with contractor name and show Issued badge
            const td = select.parentElement;
            td.textContent = contractorName;
            const actionTd = td.nextElementSibling;
            actionTd.innerHTML = '<span class="badge bg-success">Issued</span>';

            // Show success toast
            const successToast = new bootstrap.Toast(
              document.getElementById("successToast")
            );
            document.getElementById("successToastMessage").textContent =
              "Contractor assigned successfully!";
            successToast.show();
          } else {
            // Show error toast
            const errorToast = new bootstrap.Toast(
              document.getElementById("errorToast")
            );
            document.getElementById("errorToastMessage").textContent =
              data.message || "Failed to assign contractor.";
            errorToast.show();
          }
        })
        .catch((error) => {
          // Show error toast
          const errorToast = new bootstrap.Toast(
            document.getElementById("errorToast")
          );
          document.getElementById("errorToastMessage").textContent =
            "Error: " + error.message;
          errorToast.show();
        });
    });
  });
});


$(document).ready(function() {
  // Initialize DataTables
  try {
    $('#mbEntriesTable').DataTable({
      responsive: true,
      pageLength: 10,
      order: [
        [0, 'asc']
      ], // Sort by ID ascending by default
      language: {
        search: "Filter entries:",
        lengthMenu: "Show _MENU_ entries"
      }
    });
    console.log('DataTables initialized successfully.');
  } catch (e) {
    console.error('Error initializing DataTables:', e);
  }

  // Debug: Check if modal button click is registering
  $('.view-details-btn').on('click', function() {
    console.log('View Details button clicked for modal:', $(this).data('bs-target'));
    // Manually trigger the modal to test Bootstrap functionality
    try {
      var modalId = $(this).data('bs-target');
      $(modalId).modal('show');
      console.log('Manually triggered modal:', modalId);
    } catch (e) {
      console.error('Error manually triggering modal:', e);
    }
  });
});

function handleFileUpload(input) {
  const file = input.files[0];
  if (file) {
    alert(`Selected file: ${file.name}`);
    // You can now send this file via AJAX or form submission
  }
}