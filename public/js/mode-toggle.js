document.addEventListener('DOMContentLoaded', function () {
    const modeToggle = document.getElementById('mode-toggle');
    const body = document.body;

    // Check for saved mode in local storage
    if (localStorage.getItem('theme') === 'light') {
        body.classList.add('light-mode');
        updateIcon();
    }

    modeToggle.addEventListener('click', function () {
        body.classList.toggle('light-mode');
        
        // Save mode to local storage
        if (body.classList.contains('light-mode')) {
            localStorage.setItem('theme', 'light');
        } else {
            localStorage.setItem('theme', 'dark');
        }

        updateIcon();
    });

    function updateIcon() {
        const icon = modeToggle.querySelector('i');
        if (body.classList.contains('light-mode')) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    }
});
