document.addEventListener("DOMContentLoaded", function () {
    const seatContainer = document.querySelector(".seat-container");
    const seatSelectionInfo = document.getElementById("seat-selection-info");
    const reserveButton = document.getElementById("reserve_btn");

    renderSeats();

    function renderSeats() {
        seatContainer.innerHTML = '';

        for (let i = 1; i <= noSeats; i++) {
            const seatButton = document.createElement('button');
            seatButton.textContent = `Seat ${i}`;
            seatButton.classList.add('seat');

            
            if (bookedSeats.includes(i.toString())) {
                seatButton.classList.add('booked');
                seatButton.disabled = true; 
                seatButton.style.backgroundColor = 'dc3545'; 
            } else {
                seatButton.addEventListener('click', function () {
                    clearSeatSelection(); 

                    seatButton.classList.add('selected'); 
                    seatSelectionInfo.value = i; 
                    document.getElementById('seat_number').value = i; 
                    reserveButton.disabled = false; 
                });
            }

            seatContainer.appendChild(seatButton);
        }
    }

    function clearSeatSelection() {
        const selectedSeat = document.querySelector('.seat.selected');
        if (selectedSeat) {
            selectedSeat.classList.remove('selected'); // Remove selection from previous seat
        }
        seatSelectionInfo.value = ''; // Clear seat selection input
        reserveButton.disabled = true; // Disable reserve button
    }
});
