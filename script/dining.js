let nextDom = document.getElementById('next');
let prevDom = document.getElementById('prev');

let carouselDom = document.querySelector('.carousel');
let SliderDom = carouselDom.querySelector('.carousel .list');
let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');
let timeDom = document.querySelector('.carousel .time');

thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
let timeRunning = 3000;
let timeAutoNext = 5000;

nextDom.onclick = function(){
  showSlider('next');    
}

prevDom.onclick = function(){
  showSlider('prev');    
}

let runTimeOut;
let runNextAuto = setTimeout(() => {
  next.click();
}, timeAutoNext);

function showSlider(type){
  let  SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
  let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');
  
  if(type === 'next'){
      SliderDom.appendChild(SliderItemsDom[0]);
      thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
      carouselDom.classList.add('next');
  }else{
      SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
      thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
      carouselDom.classList.add('prev');
  }
  clearTimeout(runTimeOut);
  runTimeOut = setTimeout(() => {
      carouselDom.classList.remove('next');
      carouselDom.classList.remove('prev');
  }, timeRunning);

  clearTimeout(runNextAuto);
  runNextAuto = setTimeout(() => {
      next.click();
  }, timeAutoNext)
}

// DOM Elements
const dateInput = document.getElementById('reservation-date');
const timeSelect = document.getElementById('reservation-time');
const partySizeSelect = document.getElementById('party-size');
const toStep2Button = document.getElementById('to-step-2');
const backToStep1Button = document.getElementById('back-to-step-1');
const toStep3Button = document.getElementById('to-step-3');
const backToStep2Button = document.getElementById('back-to-step-2');
const toStep4Button = document.getElementById('to-step-4');
const newReservationButton = document.getElementById('new-reservation');

const dateTimeSection = document.getElementById('date-time-section');
const tableSection = document.getElementById('table-section');
const detailsSection = document.getElementById('details-section');
const confirmationSection = document.getElementById('confirmation-section');

const step1 = document.getElementById('step-1');
const step2 = document.getElementById('step-2');
const step3 = document.getElementById('step-3');
const step4 = document.getElementById('step-4');

const tables = document.querySelectorAll('.table');
const selectedTableElement = document.getElementById('selected-table');
const recommendedTablesElement = document.getElementById('recommended-tables');

// Customer form elements
const customerNameInput = document.getElementById('customer-name');
const customerEmailInput = document.getElementById('customer-email');
const customerPhoneInput = document.getElementById('customer-phone');
const specialRequestsInput = document.getElementById('special-requests');

// Confirmation elements
const confirmationCodeElement = document.getElementById('confirmation-code');
const confirmationDateElement = document.getElementById('confirmation-date');
const confirmationTimeElement = document.getElementById('confirmation-time');
const confirmationPartyElement = document.getElementById('confirmation-party');
const confirmationTableElement = document.getElementById('confirmation-table');
const confirmationNameElement = document.getElementById('confirmation-name');
const confirmationEmailElement = document.getElementById('confirmation-email');

// State
const reservationState = {
  date: '',
  time: '',
  partySize: '',
  tableId: '',
  tableSeats: '',
  customerName: '',
  customerEmail: '',
  customerPhone: '',
  specialRequests: '',
  confirmationCode: ''
};

// Sample data for occupied tables (in a real app, this would come from a database)
const today = new Date();
const formattedToday = today.toISOString().split('T')[0];

const occupiedTables = {
  'T3': [formattedToday, '19:00'],
  'T7': [formattedToday, '20:00'],
  'T10': [formattedToday, '19:30']
};

// Initialize the app
function init() {
  // Set minimum date to today
  dateInput.min = formattedToday;
  dateInput.value = formattedToday;

  // Initialize event listeners
  setupEventListeners();

  // Mark occupied tables
  updateTableAvailability();
}

function setupEventListeners() {
  // Navigation buttons
  toStep2Button.addEventListener('click', goToStep2);
  backToStep1Button.addEventListener('click', goToStep1);
  toStep3Button.addEventListener('click', goToStep3);
  backToStep2Button.addEventListener('click', goToStep2);
  toStep4Button.addEventListener('click', completeReservation);
  newReservationButton.addEventListener('click', resetReservation);

  // Form inputs for validation
  dateInput.addEventListener('change', validateStep1);
  timeSelect.addEventListener('change', validateStep1);
  partySizeSelect.addEventListener('change', validateStep1);

  // Table selection
  tables.forEach(table => {
    table.addEventListener('click', selectTable);
  });

  // When date or time changes, update table availability
  dateInput.addEventListener('change', updateTableAvailability);
  timeSelect.addEventListener('change', updateTableAvailability);

  // Party size changes recommendation
  partySizeSelect.addEventListener('change', updateTableRecommendations);

  // Form validation
  customerNameInput.addEventListener('input', validateStep3);
  customerEmailInput.addEventListener('input', validateStep3);
  customerPhoneInput.addEventListener('input', validateStep3);
}

// Navigation functions
function goToStep1() {
  hideAllSections();
  dateTimeSection.classList.remove('hidden');
  updateActiveStep(step1);
}

function goToStep2() {
  if (!validateStep1()) return;

  // Save Step 1 data
  reservationState.date = dateInput.value;
  reservationState.time = timeSelect.value;
  reservationState.partySize = partySizeSelect.value;

  hideAllSections();
  tableSection.classList.remove('hidden');
  updateActiveStep(step2);

  // Update table recommendations based on party size
  updateTableRecommendations();

  // Update table availability
  updateTableAvailability();
}

function goToStep3() {
  if (!reservationState.tableId) {
    alert('Please select a table first.');
    return;
  }

  hideAllSections();
  detailsSection.classList.remove('hidden');
  updateActiveStep(step3);
}

function completeReservation() {
  if (!validateStep3()) return;

  // Save Step 3 data
  reservationState.customerName = customerNameInput.value;
  reservationState.customerEmail = customerEmailInput.value;
  reservationState.customerPhone = customerPhoneInput.value;
  reservationState.specialRequests = specialRequestsInput.value;

  // Generate confirmation code
  reservationState.confirmationCode = generateConfirmationCode();

  // Update confirmation page
  confirmationCodeElement.textContent = reservationState.confirmationCode;
  confirmationDateElement.textContent = formatDate(reservationState.date);
  confirmationTimeElement.textContent = formatTime(reservationState.time);
  confirmationPartyElement.textContent = formatPartySize(reservationState.partySize);
  confirmationTableElement.textContent = `${reservationState.tableId} (${reservationState.tableSeats} seats)`;
  confirmationNameElement.textContent = reservationState.customerName;
  confirmationEmailElement.textContent = reservationState.customerEmail;

  hideAllSections();
  confirmationSection.classList.remove('hidden');
  updateActiveStep(step4);

  // In a real app, this is where you would send the data to a server
  console.log('Reservation Details:', reservationState);
}

function resetReservation() {
  // Reset all form fields
  timeSelect.value = '';
  partySizeSelect.value = '';
  customerNameInput.value = '';
  customerEmailInput.value = '';
  customerPhoneInput.value = '';
  specialRequestsInput.value = '';

  // Reset state
  reservationState.tableId = '';
  reservationState.tableSeats = '';

  // Reset selected table
  tables.forEach(table => {
    if (table.classList.contains('selected')) {
      table.classList.remove('selected');
      table.classList.add('available');
    }
  });
  selectedTableElement.textContent = 'None';

  // Go back to step 1
  goToStep1();
}

// Helper functions
function hideAllSections() {
  dateTimeSection.classList.add('hidden');
  tableSection.classList.add('hidden');
  detailsSection.classList.add('hidden');
  confirmationSection.classList.add('hidden');
}

function updateActiveStep(activeStep) {
  // Remove active class from all steps
  step1.classList.remove('active', 'completed');
  step2.classList.remove('active', 'completed');
  step3.classList.remove('active', 'completed');
  step4.classList.remove('active', 'completed');

  // Add completed class to previous steps
  if (activeStep === step2 || activeStep === step3 || activeStep === step4) {
    step1.classList.add('completed');
  }

  if (activeStep === step3 || activeStep === step4) {
    step2.classList.add('completed');
  }

  if (activeStep === step4) {
    step3.classList.add('completed');
  }

  // Add active class to current step
  activeStep.classList.add('active');
}

function selectTable(event) {
  const tableElement = event.currentTarget;

  // Ignore if table is occupied
  if (tableElement.classList.contains('occupied')) {
    return;
  }

  // Clear previous selection
  tables.forEach(table => {
    if (table.classList.contains('selected')) {
      table.classList.remove('selected');
      table.classList.add('available');
    }
  });

  // Select new table
  tableElement.classList.remove('available');
  tableElement.classList.add('selected');

  // Update state
  reservationState.tableId = tableElement.dataset.tableId || '';
  reservationState.tableSeats = tableElement.dataset.seats || '';

  // Update UI
  selectedTableElement.textContent = `${reservationState.tableId} (${reservationState.tableSeats} seats)`;

  // Enable next button
  toStep3Button.disabled = false;
}

function updateTableAvailability() {
  const selectedDate = dateInput.value;
  const selectedTime = timeSelect.value;

  // Reset all tables to available first
  tables.forEach(table => {
    const tableId = table.dataset.tableId || '';

    // Check if table is in occupied list
    if (occupiedTables[tableId] &&
        occupiedTables[tableId][0] === selectedDate &&
        occupiedTables[tableId][1] === selectedTime) {
      table.classList.remove('available', 'selected');
      table.classList.add('occupied');
    } else {
      // If table is not occupied and not selected, make it available
      if (!table.classList.contains('selected')) {
        table.classList.remove('occupied');
        table.classList.add('available');
      }
    }
  });
}

function updateTableRecommendations() {
  const partySize = parseInt(partySizeSelect.value);

  if (isNaN(partySize)) {
    recommendedTablesElement.textContent = 'Please select a party size first';
    return;
  }

  const recommendedTableTypes = [];

  if (partySize <= 2) {
    recommendedTableTypes.push('2-seater tables (T1-T4)');
  }

  if (partySize > 2 && partySize <= 4) {
    recommendedTableTypes.push('4-seater tables (T5-T7)');
  }

  if (partySize > 4 && partySize <= 6) {
    recommendedTableTypes.push('6-seater tables (T8-T9)');
  }

  if (partySize > 6) {
    recommendedTableTypes.push('8+ seater table (T10)');
  }

  recommendedTablesElement.textContent = recommendedTableTypes.join(', ');
}

// Form validation
function validateStep1() {
  const isDateValid = dateInput.value !== '';
  const isTimeValid = timeSelect.value !== '';
  const isPartySizeValid = partySizeSelect.value !== '';

  toStep2Button.disabled = !(isDateValid && isTimeValid && isPartySizeValid);

  return isDateValid && isTimeValid && isPartySizeValid;
}

function validateStep3() {
  const isNameValid = customerNameInput.value.trim() !== '';
  const isEmailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerEmailInput.value);
  const isPhoneValid = customerPhoneInput.value.trim().length >= 10;

  toStep4Button.disabled = !(isNameValid && isEmailValid && isPhoneValid);

  return isNameValid && isEmailValid && isPhoneValid;
}

// Formatting functions
function formatDate(dateString) {
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-US', options);
}

function formatTime(timeString) {
  const [hours, minutes] = timeString.split(':');
  const hour = parseInt(hours);
  return `${hour > 12 ? hour - 12 : hour}:${minutes} ${hour >= 12 ? 'PM' : 'AM'}`;
}

function formatPartySize(partySize) {
  return partySize === '1' ? '1 person' : `${partySize} people`;
}

function generateConfirmationCode() {
  const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  let code = '';

  for (let i = 0; i < 8; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    code += characters.charAt(randomIndex);
  }

  return code;
}

// Initialize the app
init();
