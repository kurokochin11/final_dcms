document.addEventListener('DOMContentLoaded', () => {

    // Smooth transition when switching steps
    const stepContainers = document.querySelectorAll('[x-show]');

    stepContainers.forEach(container => {
        container.style.transition = 'opacity 0.25s ease-in-out';
    });

    // Optional: scroll to top when step changes
    document.addEventListener('click', (e) => {
        if (e.target.matches('button')) {
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        }
    });

});
