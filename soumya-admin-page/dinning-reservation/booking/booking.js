function validatePhoneNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
    if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}
function validateName(input) {
    input.value = input.value.replace(/[^a-zA-Z ]/g, '');
}