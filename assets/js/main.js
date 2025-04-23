// Construction Project Management System JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    initTooltips();
    
    // Initialize alert dismissal
    initAlertDismissal();
    
    // Initialize sidebar toggle for mobile
    initSidebarToggle();
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize workflow progress animations
    initWorkflowAnimations();
});

// Initialize tooltips
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltipElement = document.createElement('div');
            tooltipElement.className = 'tooltip';
            tooltipElement.textContent = tooltipText;
            
            document.body.appendChild(tooltipElement);
            
            const rect = this.getBoundingClientRect();
            tooltipElement.style.top = `${rect.top - tooltipElement.offsetHeight - 10}px`;
            tooltipElement.style.left = `${rect.left + (rect.width / 2) - (tooltipElement.offsetWidth / 2)}px`;
            tooltipElement.style.opacity = '1';
        });
        
        tooltip.addEventListener('mouseleave', function() {
            const tooltipElement = document.querySelector('.tooltip');
            if (tooltipElement) {
                tooltipElement.remove();
            }
        });
    });
}

// Initialize alert dismissal
function initAlertDismissal() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

// Initialize sidebar toggle for mobile
function initSidebarToggle() {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
}

// Initialize form validation
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let hasError = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    e.preventDefault();
                    hasError = true;
                    
                    field.classList.add('error');
                    
                    const errorMessage = field.getAttribute('data-error-message') || 'This field is required';
                    let errorElement = field.nextElementSibling;
                    
                    if (!errorElement || !errorElement.classList.contains('form-error')) {
                        errorElement = document.createElement('div');
                        errorElement.className = 'form-error';
                        field.parentNode.insertBefore(errorElement, field.nextSibling);
                    }
                    
                    errorElement.textContent = errorMessage;
                } else {
                    field.classList.remove('error');
                    const errorElement = field.nextElementSibling;
                    if (errorElement && errorElement.classList.contains('form-error')) {
                        errorElement.remove();
                    }
                }
            });
            
            if (hasError) {
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.focus();
                }
            }
        });
    });
}

// Initialize workflow progress animations
function initWorkflowAnimations() {
    const workflowSteps = document.querySelectorAll('.workflow-step');
    
    workflowSteps.forEach((step, index) => {
        setTimeout(() => {
            step.classList.add('fade-in');
        }, index * 200);
    });
}

// Handle dynamic form fields
function addFormField(containerId, template) {
    const container = document.getElementById(containerId);
    const newField = document.createElement('div');
    newField.className = 'form-row';
    newField.innerHTML = template;
    
    container.appendChild(newField);
    
    // Initialize any new event listeners for the added field
    const removeBtn = newField.querySelector('.remove-field');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            newField.remove();
        });
    }
}

// Format currency input
function formatCurrency(input) {
    let value = input.value.replace(/[^\d.]/g, '');
    
    if (value) {
        value = parseFloat(value).toFixed(2);
        input.value = value;
    }
}

// Confirm action with modal
function confirmAction(message, callback) {
    const confirmed = confirm(message);
    
    if (confirmed && typeof callback === 'function') {
        callback();
    }
}

// Toggle visibility of an element
function toggleElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.toggle('hidden');
    }
}

// Dynamic search functionality
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tbody tr');
    
    input.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

// Print functionality
function printElement(elementId) {
    const element = document.getElementById(elementId);
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<link rel="stylesheet" href="assets/css/styles.css">');
    printWindow.document.write('<style>body { padding: 20px; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(element.innerHTML);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}