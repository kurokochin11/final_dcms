document.addEventListener('DOMContentLoaded', () => {

    // Scroll to top when Next / Back is clicked
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', () => {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 50);
        });
    });

});
