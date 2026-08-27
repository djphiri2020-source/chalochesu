// assets/js/form-validation.js

class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        if (!this.form) return;
        
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', (e) => this.validateForm(e));
        
        // Add real-time validation
        const inputs = this.form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }
    
    validateForm(e) {
        e.preventDefault();
        
        let isValid = true;
        const inputs = this.form.querySelectorAll('[required], input, textarea, select');
        
        // Clear previous errors
        this.clearAllErrors();
        
        // Validate each field
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        if (isValid) {
            this.submitForm();
        }
        
        return false;
    }
    
    validateField(field) {
        let isValid = true;
        let errorMessage = '';
        
        // Check if field is required
        if (field.hasAttribute('required') && !field.value.trim()) {
            errorMessage = 'This field is required';
            isValid = false;
        }
        
        // Check email format
        if (field.type === 'email' && field.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                errorMessage = 'Please enter a valid email address';
                isValid = false;
            }
        }
        
        // Check phone number
        if (field.type === 'tel' && field.value.trim()) {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
            if (!phoneRegex.test(field.value)) {
                errorMessage = 'Please enter a valid phone number';
                isValid = false;
            }
        }
        
        // Check date fields
        if (field.type === 'date' && field.value) {
            const selectedDate = new Date(field.value);
            const today = new Date();
            if (selectedDate < today) {
                errorMessage = 'Please select a future date';
                isValid = false;
            }
        }
        
        // Check number of guests
        if (field.name === 'guests' && field.value) {
            const guests = parseInt(field.value);
            if (guests < 1) {
                errorMessage = 'Please select at least 1 guest';
                isValid = false;
            }
        }
        
        // Check message length
        if (field.type === 'textarea' && field.value.trim()) {
            if (field.value.trim().length < 10) {
                errorMessage = 'Message should be at least 10 characters';
                isValid = false;
            }
        }
        
        if (!isValid) {
            this.showFieldError(field, errorMessage);
        } else {
            this.clearFieldError(field);
        }
        
        return isValid;
    }
    
    showFieldError(field, message) {
        // Remove any existing error
        this.clearFieldError(field);
        
        // Add error class to field
        field.classList.add('error');
        
        // Create error message element
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        errorElement.style.color = '#dc3545';
        errorElement.style.fontSize = '0.875rem';
        errorElement.style.marginTop = '0.25rem';
        
        // Insert after field
        field.parentNode.insertBefore(errorElement, field.nextSibling);
        
        // Scroll to error
        if (field.getBoundingClientRect().top < 0) {
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    clearFieldError(field) {
        field.classList.remove('error');
        
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    clearAllErrors() {
        const errors = this.form.querySelectorAll('.field-error');
        errors.forEach(error => error.remove());
        
        const errorFields = this.form.querySelectorAll('.error');
        errorFields.forEach(field => field.classList.remove('error'));
    }
    
    submitForm() {
        const formData = new FormData(this.form);
        const submitBtn = this.form.querySelector('button[type="submit"]');
        
        // Disable submit button
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        // In production, this would be an AJAX call to your server
        // For now, we'll simulate a successful submission
        
        setTimeout(() => {
            // Show success message
            this.showSuccessMessage();
            
            // Reset form
            this.form.reset();
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            // Send data to server in production
            // this.sendToServer(formData);
        }, 1500);
    }
    
    showSuccessMessage() {
        // Remove any existing messages
        const existingMessage = this.form.querySelector('.form-message');
        if (existingMessage) existingMessage.remove();
        
        // Create success message
        const messageDiv = document.createElement('div');
        messageDiv.className = 'form-message success-message';
        messageDiv.innerHTML = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Thank you for your enquiry!</strong>
                    <p>We've received your message and will contact you within 24 hours.</p>
                </div>
            </div>
        `;
        
        // Insert at beginning of form
        this.form.insertBefore(messageDiv, this.form.firstChild);
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Remove message after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
    
    sendToServer(formData) {
        // In production, implement AJAX submission
        fetch('/submit-form.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSuccessMessage();
                this.form.reset();
            } else {
                this.showServerError(data.message);
            }
        })
        .catch(error => {
            this.showServerError('An error occurred. Please try again.');
            console.error('Error:', error);
        });
    }
    
    showServerError(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'form-message error-message';
        messageDiv.innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Submission Failed</strong>
                    <p>${message}</p>
                </div>
            </div>
        `;
        
        this.form.insertBefore(messageDiv, this.form.firstChild);
    }
}

// Initialize form validators
document.addEventListener('DOMContentLoaded', function() {
    // Contact form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        new FormValidator('contactForm');
    }
    
    // Newsletter form
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!email.value || !emailRegex.test(email.value)) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Simulate subscription
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            setTimeout(() => {
                alert('Thank you for subscribing to our newsletter!');
                this.reset();
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 1000);
        });
    }
    
    // Booking form date validation
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        // Set min date to today
        const today = new Date().toISOString().split('T')[0];
        input.min = today;
        
        // Add date picker enhancement
        input.addEventListener('focus', function() {
            this.showPicker();
        });
    });
    
    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            // Format based on input length
            if (value.length > 0) {
                value = '+' + value;
            }
            
            this.value = value;
        });
    });
    
    // Character counters for textareas
    const textareas = document.querySelectorAll('textarea[data-maxlength]');
    textareas.forEach(textarea => {
        const maxLength = textarea.dataset.maxlength;
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.fontSize = '0.8rem';
        counter.style.color = '#666';
        counter.style.textAlign = 'right';
        counter.style.marginTop = '0.25rem';
        
        textarea.parentNode.appendChild(counter);
        
        const updateCounter = () => {
            const currentLength = textarea.value.length;
            counter.textContent = `${currentLength}/${maxLength} characters`;
            
            if (currentLength > maxLength * 0.9) {
                counter.style.color = '#ff9800';
            } else {
                counter.style.color = '#666';
            }
            
            if (currentLength > maxLength) {
                counter.style.color = '#f44336';
                textarea.value = textarea.value.substring(0, maxLength);
            }
        };
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
});

// Additional form styling
const style = document.createElement('style');
style.textContent = `
    input.error,
    textarea.error,
    select.error {
        border-color: #f44336 !important;
        background-color: #fff8f8;
    }
    
    .field-error {
        color: #f44336;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .field-error:before {
        content: "⚠";
        font-size: 0.875rem;
    }
    
    .success-message .alert {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .success-message .alert i {
        font-size: 1.5rem;
        color: #28a745;
    }
    
    .error-message .alert {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .error-message .alert i {
        font-size: 1.5rem;
        color: #dc3545;
    }
    
    .form-message {
        margin-bottom: 1.5rem;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Custom select styling */
    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px;
        padding-right: 2.5rem;
    }
    
    /* Focus states */
    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: var(--sunset-orange);
        box-shadow: 0 0 0 3px rgba(217, 108, 41, 0.1);
    }
    
    /* Placeholder styling */
    ::placeholder {
        color: #999;
    }
    
    :-ms-input-placeholder {
        color: #999;
    }
    
    ::-ms-input-placeholder {
        color: #999;
    }
`;

document.head.appendChild(style);