document.addEventListener('change', (e) => {
    if (e.target.type === 'radio') {
        const parent = e.target.closest('.mb-4');
        const textField = parent ? parent.querySelector('input[type="text"], textarea') : null;
        
        if (textField) {
            if (e.target.value === 'No') {
                // Lock the field
                textField.readOnly = true;
                
                // Visual cues
                textField.style.opacity = '0.5';
                textField.style.backgroundColor = '#f3f4f6';
                textField.style.cursor = 'not-allowed';
                
                // Optional: Clear the text if they select "No"
                // textField.value = ''; 
            } else {
                // Unlock the field
                textField.readOnly = false;
                
                // Reset Visuals
                textField.style.opacity = '1';
                textField.style.backgroundColor = '#ffffff';
                textField.style.cursor = 'text';
            }
        }
    }
});