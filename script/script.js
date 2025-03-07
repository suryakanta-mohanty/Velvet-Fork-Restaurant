function toggleDropdown() {
    var dropdown = document.getElementById("profileDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
  }

  document.addEventListener("click", function(event) {
    var profile = document.querySelector(".profile");
    var dropdown = document.getElementById("profileDropdown");
    if (!profile.contains(event.target)) {
      dropdown.style.display = "none";
    }
  });

  function openModal(id) {
    document.getElementById(id).style.display = "block";
  }

  function closeModal(id) {
    document.getElementById(id).style.display = "none";
  }