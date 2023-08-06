<?php
session_start();
$page_title = 'Search Pending Equipment';
include("./includes/header.php");
include("./includes/navbar.php");
?>
    <div class="container mt-5">
        <h1 class="text-center">Search Overdue Equipment</h1>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Enter equipment name or make">
            </div>
        </div>
        <div class="row justify-content-center" id="equipmentCardContainer"></div>
    </div>

    <!-- Include Bootstrap JS and your custom script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to send email to the student
        function sendEmailToStudent(email) {
            // Fetch overdue items based on student's email
            fetch('sendOverdueItemsEmail.php?email=' + email)
                .then(function(response) {
                    if (response.ok) {
                        alert('Email sent successfully!');
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                })
                .catch(function(error) {
                    console.log('Error:', error.message);
                });
        }

        // Function to search pending equipment
        function searchPendingEquipment() {
            var searchValue = document.getElementById('searchInput').value;

            // Fetch pending equipment based on search value
            fetch('searchPendingEquipment.php?search=' + searchValue)
                .then(function(response) {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                })
                .then(function(equipmentData) {
                    // Generate HTML for equipment cards
                    var cardHtml = '';
                    equipmentData.forEach(function(row) {
                        cardHtml += `
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header text-success">${row.names}</div>
                                    <div class="card-body">
                                        <p class="card-text">Student Number: ${row.student_number}</p>
                                        <p class="card-text"> ${row.email}</p>
                                        <p class="card-text"> ${row.phone_number}</p>
                                        <p class="card-text "><b>Name:</b> ${row.equipment_name}</p>
                                        <p class="card-text"><b>Make:</b> ${row.equipment_make}</p>
                                        <p class="card-text"><b>Number:</b> <span class="badge bg-danger">${row.number}</span></p>
                                        <p class="card-text text-danger">Deadline: ${row.deadline}</p>
                                        <a href="#" class="btn btn-primary" onclick="sendEmailToStudent('${row.email}')">Email</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    // Update the equipment card container with the search results
                    document.getElementById('equipmentCardContainer').innerHTML = cardHtml;
                })
                .catch(function(error) {
                    console.log('Error:', error.message);
                });
        }

        // Event listener for search input keyup
        var searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function(event) {
            searchPendingEquipment();
        });

        // Initial search on page load
        searchPendingEquipment();
    </script>
    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
