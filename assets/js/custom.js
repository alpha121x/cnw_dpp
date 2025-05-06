document.addEventListener("DOMContentLoaded", function() {
  // Handle issuance status update
  document.querySelectorAll('.issuance-select').forEach(select => {
    if (!select.disabled) { // Only add event listener to non-disabled selects
      select.addEventListener('change', function() {
        const workOrderId = this.getAttribute('data-work-order-id');
        const isIssued = this.value === 'true';

        fetch('services/update_workorder_issuance.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `work_order_id=${workOrderId}&is_issued=${isIssued}`
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              const toastEl = document.getElementById("successToast");
              const toastBody = document.getElementById("successToastMessage");
              toastBody.textContent = "Issuance status updated successfully.";
              const toast = new bootstrap.Toast(toastEl);
              toast.show();

              // Disable the dropdown after successful update
              this.disabled = true;

              // Update the UI to reflect the new status (optional, since reload will handle it)
              setTimeout(() => location.reload(), 1500);
            } else {
              const toastEl = document.getElementById("errorToast");
              const toastBody = document.getElementById("errorToastMessage");
              toastBody.textContent = data.error || "Failed to update issuance status.";
              const toast = new bootstrap.Toast(toastEl);
              toast.show();

              // Revert to original value if update fails
              this.value = !isIssued ? 'true' : 'false';
            }
          })
          .catch(error => {
            const toastEl = document.getElementById("errorToast");
            const toastBody = document.getElementById("errorToastMessage");
            toastBody.textContent = "Error updating issuance status: " + error.message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Revert to original value if update fails
            this.value = !isIssued ? 'true' : 'false';
          });
      });
    }
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

document.addEventListener('DOMContentLoaded', function() {
  // Handle issuance status update
  document.querySelectorAll('.issuance-switch').forEach(switchElement => {
    if (!switchElement.disabled) {
      switchElement.addEventListener('change', function() {
        const workOrderId = this.getAttribute('data-work-order-id');
        const isIssued = this.checked;

        fetch('services/update_workorder_issuance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `work_order_id=${workOrderId}&is_issued=${isIssued}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const toastEl = document.getElementById("successToast");
            const toastBody = document.getElementById("successToastMessage");
            toastBody.textContent = "Issuance status updated successfully.";
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Disable the switch after successful update
            this.disabled = true;
            const label = this.nextElementSibling;
            label.textContent = isIssued ? 'Issued' : 'Not Issued';

            setTimeout(() => location.reload(), 1500);
          } else {
            const toastEl = document.getElementById("errorToast");
            const toastBody = document.getElementById("errorToastMessage");
            toastBody.textContent = data.error || "Failed to update issuance status.";
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Revert to original value if update fails
            this.checked = !isIssued;
          }
        })
        .catch(error => {
          const toastEl = document.getElementById("errorToast");
          const toastBody = document.getElementById("errorToastMessage");
          toastBody.textContent = "Error updating issuance status: " + error.message;
          const toast = new bootstrap.Toast(toastEl);
          toast.show();

          // Revert to original value if update fails
          this.checked = !isIssued;
        });
      });
    }
  });

  // Handle PDF generation
  document.querySelectorAll('.generate-pdf').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const workOrderId = this.getAttribute('data-work-order-id');

      fetch('services/generate_workorder_pdf.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `work_order_id=${workOrderId}`
      })
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.blob();
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `work_order_${workOrderId}.pdf`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        const toastEl = document.getElementById("errorToast");
        const toastBody = document.getElementById("errorToastMessage");
        toastBody.textContent = "Error generating PDF: " + error.message;
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
      });
    });
  });
});