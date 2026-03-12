document.addEventListener('change', (e) => {
    // Check if the change happened on a radio button
    if (e.target.type === 'radio' && e.target.value === 'No') {
        const parent = e.target.closest('.mb-4');
        const textField = parent ? parent.querySelector('input[type="text"], textarea') : null;
        
        if (textField) {
            // Functional fix: Disable the field and clear value
            textField.disabled = true;
            textField.value = ''; 
        }
    } else if (e.target.type === 'radio' && e.target.value === 'Yes') {
        const parent = e.target.closest('.mb-4');
        const textField = parent ? parent.querySelector('input[type="text"], textarea') : null;
        
        if (textField) {
            textField.disabled = false;
        }
    }
});