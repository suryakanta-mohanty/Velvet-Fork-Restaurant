document.getElementById("addItem").addEventListener("click", function () {
    let name = document.getElementById("itemName").value;
    let price = document.getElementById("itemPrice").value;
    let description = document.getElementById("itemDescription").value;
    let imageInput = document.getElementById("itemImage");

    if (name && price && description && imageInput.files.length > 0) {
        let reader = new FileReader();
        reader.onload = function (e) {
            let menuGrid = document.getElementById("menuGrid");
            let newItem = document.createElement("div");
            newItem.classList.add("menu-item");
            newItem.innerHTML = `
                <img src="${e.target.result}" alt="${name}">
                <strong>Item Code: ${Math.floor(Math.random() * 900 + 100)}</strong>
                <p>Name: ${name}</p>
                <p>Price: $${price}</p>
                <p>Description: ${description}</p>
                <button class="remove">Remove</button>
            `;

            menuGrid.appendChild(newItem);

            // Clear input fields
            document.getElementById("itemName").value = "";
            document.getElementById("itemPrice").value = "";
            document.getElementById("itemDescription").value = "";
            document.getElementById("itemImage").value = "";

            // Add event listener for remove button
            newItem.querySelector(".remove").addEventListener("click", function () {
                newItem.remove();
            });
        };
        reader.readAsDataURL(imageInput.files[0]);
    } else {
        alert("Please fill out all fields and select an image.");
    }
});
