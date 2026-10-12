// Function 1: Display dynamic info according to selected vehicle type
function handleVehicleTypeChange() {
  const vehicleType = document.getElementById("vehicleType").value;
  const typeInfo = document.getElementById("typeInfo");

  if (vehicleType === "Saloon Car") {
    typeInfo.textContent =
      "Standard maintenance pricing and compact service bay assigned.";
  } else if (vehicleType === "SUV") {
    typeInfo.textContent =
      "Heavy-duty suspension and extra fluid volume guidelines apply.";
  } else if (vehicleType === "Pickup") {
    typeInfo.textContent =
      "Commercial utility and payload capacity diagnostic assessment included.";
  } else if (vehicleType === "Van") {
    typeInfo.textContent = "Commercial fleet servicing protocol applied.";
  } else {
    typeInfo.textContent = "";
  }
}

// Function 2: Evaluate vehicle age and display warning for older vehicles (>15 years)
function evaluateVehicleAge() {
  const yearInput = document.getElementById("manufactureYear").value;
  const ageWarning = document.getElementById("ageWarning");
  const currentYear = new Date().getFullYear();

  if (yearInput && yearInput > 1900 && yearInput <= currentYear) {
    const age = currentYear - parseInt(yearInput);
    if (age > 15) {
      ageWarning.textContent = `Warning: Vehicle is ${age} years old (>15 years). Additional maintenance care recommended.`;
    } else {
      ageWarning.textContent = "";
    }
  } else {
    ageWarning.textContent = "";
  }
}

// Function 3: Calculate service estimate automatically when services are selected/deselected
function calculateServiceCost() {
  const checkboxes = document.querySelectorAll(
    'input[name="services[]"]:checked',
  );
  const serviceCountSpan = document.getElementById("serviceCount");
  const selectedListDiv = document.getElementById("selectedServicesList");
  const estimatedTotalSpan = document.getElementById("estimatedTotal");

  let total = 0;
  let selectedNames = [];
  // Check if Full Service is selected
  let hasFullService =
    checkboxes.length > 0 &&
    Array.from(checkboxes).some((cb) => cb.value === "Full Service");

  checkboxes.forEach((cb) => {
    let price = parseFloat(cb.getAttribute("data-price")) || 0;

    // Apply Rule 6: Free Computer Diagnosis if Full Service is selected
    if (hasFullService && cb.value === "Computer Diagnosis") {
      price = 0;
    }

    total += price;
    selectedNames.push(cb.value);
  });

  serviceCountSpan.textContent = checkboxes.length;

  if (selectedNames.length > 0) {
    selectedListDiv.textContent = selectedNames.join(", ");
  } else {
    selectedListDiv.textContent = "None selected";
  }

  estimatedTotalSpan.textContent = `KSh ${total.toLocaleString()}`;
}

// Function 4: Validate service form inputs and prevent submission if invalid
function validateServiceForm(event) {
  let isValid = true;

  document.getElementById("nameError").textContent = "";
  document.getElementById("phoneError").textContent = "";
  document.getElementById("emailError").textContent = "";
  document.getElementById("regError").textContent = "";
  document.getElementById("typeError").textContent = "";
  document.getElementById("yearError").textContent = "";
  document.getElementById("dateError").textContent = "";
  document.getElementById("serviceError").textContent = "";

  const customerName = document.getElementById("customerName").value.trim();
  if (!customerName) {
    document.getElementById("nameError").textContent =
      "Customer name is required.";
    isValid = false;
  }

  const phoneNumber = document.getElementById("phoneNumber").value.trim();
  const phoneRegex = /^\d{10}$/;
  if (!phoneRegex.test(phoneNumber)) {
    document.getElementById("phoneError").textContent =
      "Phone number must contain exactly 10 digits.";
    isValid = false;
  }

  const email = document.getElementById("email").value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    document.getElementById("emailError").textContent =
      "Please enter a valid email address.";
    isValid = false;
  }

  const regNumber = document.getElementById("regNumber").value.trim();
  if (!regNumber) {
    document.getElementById("regError").textContent =
      "Vehicle registration number is required.";
    isValid = false;
  }

  const vehicleType = document.getElementById("vehicleType").value;
  if (!vehicleType) {
    document.getElementById("typeError").textContent =
      "Please select a vehicle type.";
    isValid = false;
  }

  const manufactureYear = parseInt(
    document.getElementById("manufactureYear").value,
  );
  const currentYear = new Date().getFullYear();
  if (
    isNaN(manufactureYear) ||
    manufactureYear < 1950 ||
    manufactureYear > currentYear
  ) {
    document.getElementById("yearError").textContent =
      `Please enter a reasonable year of manufacture (1950 - ${currentYear}).`;
    isValid = false;
  }

  const serviceDate = document.getElementById("serviceDate").value;
  if (!serviceDate) {
    document.getElementById("dateError").textContent =
      "Please select a preferred service date.";
    isValid = false;
  }

  const selectedServices = document.querySelectorAll(
    'input[name="services[]"]:checked',
  );
  if (selectedServices.length === 0) {
    document.getElementById("serviceError").textContent =
      "Please select at least one service.";
    isValid = false;
  }

  if (!isValid) {
    event.preventDefault();
  }

  return isValid;
}

document.addEventListener("DOMContentLoaded", () => {
  calculateServiceCost();
});
