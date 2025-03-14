document.querySelectorAll('.table-option, .party-hall-option').forEach(option => {
    option.addEventListener('mouseenter', () => {
        let button = option.querySelector('.book-now');
        if (button) button.style.display = 'block';
    });
    option.addEventListener('mouseleave', () => {
        let button = option.querySelector('.book-now');
        if (button) button.style.display = 'none';
    });
});
    function openBookingPage(tableType) {
        window.location.href = `/booking/boking.html?table=${encodeURIComponent(tableType)}`;
    }