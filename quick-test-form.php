<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #074461; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0a5a7a; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
    <script src="js/jquery-3.6.1.min.js"></script>
</head>
<body>
    <h1>Contact Form Test</h1>
    <p>This is a simple test form to verify the contact form is working correctly with the service package field included.</p>
    
    <form class="contact-form cons-contact-form" method="post" action="form-submit.php">
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" name="name" id="name" required value="Test User">
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number (10 digits) *</label>
            <input type="tel" name="phone" id="phone" required pattern="[0-9]{10}" maxlength="10" title="Please enter exactly 10 digits" value="9876543210">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" name="email" id="email" required value="test@example.com">
        </div>
        
        <div class="form-group">
            <label for="service">Service Package</label>
            <select name="service" id="service">
                <option value="">Select a package (optional)</option>
                <option value="Basic Package - ₹2,249/sq.ft">Basic Package - ₹2,249/sq.ft (Essential Construction)</option>
                <option value="Standard Package - ₹2,449/sq.ft" selected>Standard Package - ₹2,449/sq.ft (Complete Construction)</option>
                <option value="Premium Package - ₹2,649/sq.ft">Premium Package - ₹2,649/sq.ft (Luxury Construction)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="message">Your Message *</label>
            <textarea name="message" id="message" rows="4" required>I am interested in your construction services. Please provide more information about the selected package.</textarea>
        </div>
        
        <button type="submit">Send Test Message</button>
    </form>

    <script>
    // Phone number validation
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('phone');
        
        if (phoneInput) {
            // Allow only numbers and restrict to 10 digits
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.slice(0, 10);
                }
                e.target.value = value;
            });
            
            // Prevent non-numeric characters
            phoneInput.addEventListener('keypress', function(e) {
                if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }
    });

    // Use the same AJAX handler as the main contact form
    jQuery(document).on('submit', 'form.cons-contact-form', function(e){
        e.preventDefault();
        var form = jQuery(this);
        
        // Remove any existing alerts
        jQuery('.alert').remove();
        
        jQuery.ajax({
            url: 'form-submit.php',
            data: form.serialize(),
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function() {
                jQuery('button[type="submit"]').text('Sending...').prop('disabled', true);
            },
            success: function(data){
                jQuery('button[type="submit"]').text('Send Test Message').prop('disabled', false);
                
                if(data['success']){
                    jQuery("<div class='alert alert-success'>"+data['message']+"</div>").insertBefore('form.cons-contact-form');
                    // Reset form on success
                    form[0].reset();
                } else {
                    jQuery("<div class='alert alert-danger'>"+data['message']+"</div>").insertBefore('form.cons-contact-form');
                }
            },
            error: function() {
                jQuery('button[type="submit"]').text('Send Test Message').prop('disabled', false);
                jQuery("<div class='alert alert-danger'>Network error. Please try again.</div>").insertBefore('form.cons-contact-form');
            }
        });
        
        return false;
    });
    </script>
</body>
</html>
