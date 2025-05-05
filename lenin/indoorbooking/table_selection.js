document.addEventListener("DOMContentLoaded", function (){
    const today = new Date().toISOString().split('T')[0];
    const bookingDateInput = document.getElementById("bookingDate");
    const fromTimeInput = document.getElementById("fromTime");
    const toTimeInput = document.getElementById("toTime");

    bookingDateInput.setAttribute("min", today);

    bookingDateInput.addEventListener("change", function () {
        const selectedDate = this.value;
        if (selectedDate === today) {
            const now = new Date();
            const currentHours = String(now.getHours()).padStart(2, '0');
            const currentMinutes = String(now.getMinutes()).padStart(2, '0');
            const minTime = `${currentHours}:${currentMinutes}`;
            fromTimeInput.setAttribute("min", minTime);
        } else {
            fromTimeInput.removeAttribute("min");
        }
        fromTimeInput.value = "";
        toTimeInput.value = "";
    });

    // fromTimeInput.addEventListener("change", function () {
    //     toTimeInput.setAttribute("min", this.value);
    // });
    fromTimeInput.addEventListener("change", function () {
        if (this.value) {
            const [hours, minutes] = this.value.split(':').map(Number);
            const fromDate = new Date();
            fromDate.setHours(hours);
            fromDate.setMinutes(minutes);
            fromDate.setSeconds(0);
            fromDate.setMilliseconds(0);
    
            // Add 30 minutes
            const toDate = new Date(fromDate.getTime() + 30 * 60 * 1000);
    
            const minToHours = String(toDate.getHours()).padStart(2, '0');
            const minToMinutes = String(toDate.getMinutes()).padStart(2, '0');
            const minToTime = `${minToHours}:${minToMinutes}`;
    
            toTimeInput.value = ""; // Reset previous selection
            toTimeInput.setAttribute("min", minToTime);
        } else {
            toTimeInput.removeAttribute("min");
        }
    });
    
    
    

    function generateTables(containerId, prefix, count) {
        let container = document.getElementById(containerId);
        for (let i = 1; i <= count; i++) {
            let table = document.createElement("div");
            table.className = `table-box ${prefix}-seat-table available`;
            table.id = `table${prefix}-${i}`;
            table.textContent = i;
            table.addEventListener("click", tableClickHandler);
            container.appendChild(table);
          
            
        }
    }

    generateTables("six-seater", "6", 8);
    generateTables("four-seater", "4", 12);
    generateTables("two-seater", "2", 12);

    function tableClickHandler() {
        if (!this.classList.contains("booked")) {
            let tableId = this.id;
            let date = bookingDateInput.value;
            let fromTime = fromTimeInput.value;
            let toTime = toTimeInput.value;

            if (!date || !fromTime || !toTime) {
                alert("Please select date and time first.");
                return;
            }

            window.location.href = `tablebookingform.html?table_id=${tableId}&date=${date}&fromTime=${fromTime}&toTime=${toTime}`;
        } else {
            alert("This table is already booked.");
        }
    }

    window.checkAvailability = function () {
        let date = document.getElementById("bookingDate").value;
        let startTime = document.getElementById("fromTime").value;
        let endTime = document.getElementById("toTime").value;
    
        if (!date || !startTime || !endTime) {
            alert("Please select a date and time.");
            return;
        }
        
        // Strict check: endTime must be at least 30 minutes after startTime
        const [startHours, startMinutes] = startTime.split(":").map(Number);
        const [endHours, endMinutes] = endTime.split(":").map(Number);
        
        const startDate = new Date();
        startDate.setHours(startHours, startMinutes, 0);
        
        const endDate = new Date();
        endDate.setHours(endHours, endMinutes, 0);
        
        const diffMs = endDate - startDate;
        const diffMins = diffMs / (1000 * 60);
        
        if (diffMins < 30) {
            alert("To Time must be at least 30 minutes after From Time.");
            return;
        }
        
    
        fetch("check_availability.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `bookingDate=${encodeURIComponent(date)}&fromTime=${encodeURIComponent(startTime)}&toTime=${encodeURIComponent(endTime)}`
        })
        .then(response => response.json())
        .then(data => {
            // Reset all tables to available
            document.querySelectorAll(".table-box").forEach(table => {
                table.style.backgroundColor = "#4CAF50"; // green
                table.classList.remove("booked");
                table.classList.add("available");
            });
    
            // Mark reserved tables red
            data.reserved.forEach(tableId => {
                let tableElement = document.getElementById(tableId);
                if (tableElement) {
                    tableElement.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
                    tableElement.classList.remove("available");
                    tableElement.classList.add("booked");
                }
            });
        })
        .catch(error => console.error("Error:", error));
    };
    
    });
