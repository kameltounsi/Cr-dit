<?php
session_start();

// Supposons que vous stockez les rôles dans une session après la connexion de l'utilisateur
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'Admin' && $user_role !== 'Agent de location') {
    // Redirigez l'utilisateur vers la page d'accueil ou une page d'erreur
    header("Location: ../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - User List</title>
    <link rel="icon" href="/car_rent/img/top-logo1.png" type="image/png">
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>  
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="logo-apple"></ion-icon>
                        </span>
                        <span class="title">Brand Name</span>
                    </a>
                </li>

                <li>
                    <a href="index.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <?php if ($user_role === 'Admin') { ?>
                    <li>
                        <a href="customers.php">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">Customers</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($user_role === 'Agent de location') { ?>
                    <li>
                        <a href="../back/voitures.php">
                            <span class="icon">
                                <ion-icon name="car-sport-outline"></ion-icon>
                            </span>
                            <span class="title">Cars</span>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <span class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </span>
                            <span class="title">Reservations</span>
                        </a>
                    </li>
                <?php } ?>

                <li>
                    <a href="../index.html">
                        <span class="icon">
                            <ion-icon name="business-outline"></ion-icon>
                        </span>
                        <span class="title">Go to Front</span>
                    </a>
                </li>

                <li>
                    <a href="#" id="signOutLink">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <script src="assets/js/main.js"></script>

<!-- ====== ionicons ======= -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
                    <label>
                        <select id="search-type" class="custom-select">
                            <option value="email">Search by email</option>
                            <option value="role">Search by role</option>
                        </select>
                        
                        <input type="text" id="search-input" placeholder="Search...">
                    </label>
                </div>
                
                
                <div class="user">
    <img src="<?php echo htmlspecialchars('../' . ($_SESSION['user_pdp'] ?? 'assets/imgs/default_profile.jpg')); ?>" alt="User Profile">
</div>
            </div>

            <!-- ================ User Table ================== -->
            <div class="details">
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Customer List</h2>
                        <button id="show-stats-btn" class="stats-btn">Show Statistics</button>

                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Profile Picture</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th> <!-- New column for actions -->
                            </tr>
                        </thead>                        
                        <tbody id="user-table-body">
                            <!-- Les lignes seront générées dynamiquement par JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- Statistics Modal -->
<div id="stats-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>User Reservations</h2>
        <canvas id="stats-chart"></canvas>
    </div>
</div>

<style>
    /* Style pour le modal */
.modal {
    display: none; /* Caché par défaut */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* Adjusted margin for positioning */
    padding: 20px;
    border: 1px solid #888;
    width: 50%; /* Reduced width */
    max-width: 600px; /* Maximum width */
    height: 400px; /* Fixed height */
    overflow: auto; /* Scrollable if content overflows */
}


.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}


#stats-chart {
    width: 100%;
    height: 100%;
}
.stats-btn {
    background-color: #4CAF50; /* Green */
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 10px 0;
    cursor: pointer;
}

.stats-btn:hover {
    background-color: #45a049;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const showStatsBtn = document.getElementById('show-stats-btn');
    const statsModal = document.getElementById('stats-modal');
    const closeModalBtn = document.querySelector('.close-btn');
    const statsChartCanvas = document.getElementById('stats-chart');
    let statsChart;

    // Show modal and fetch statistics
    showStatsBtn.addEventListener('click', () => {
        statsModal.style.display = 'block';

        // Fetch reservation statistics
        fetch('../user_reservations_stats.php')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(user => user.mail);
                const reservationCounts = data.map(user => user.reservation_count);

                // Destroy existing chart if any
                if (statsChart) {
                    statsChart.destroy();
                }

                // Create new chart
                statsChart = new Chart(statsChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Reservations Count',
                            data: reservationCounts,
                            backgroundColor: labels.map((_, index) => `hsl(${(index * 360 / labels.length) % 360}, 70%, 70%)`),
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) { return value; }
                                }
                            }
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching user reservation stats:', error));
    });

    // Close modal
    closeModalBtn.addEventListener('click', () => {
        statsModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === statsModal) {
            statsModal.style.display = 'none';
        }
    });
});

</script>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <!-- Script to populate the user table -->
    <script>const baseURL = '../';  // Adjusted path to go up one directory level

document.addEventListener('DOMContentLoaded', () => {
    const searchTypeSelect = document.getElementById('search-type');
    const searchInput = document.getElementById('search-input');
    const userTableBody = document.getElementById('user-table-body');

    let users = [];

    // Function to filter table rows
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const searchType = searchTypeSelect.value;

        userTableBody.querySelectorAll('tr').forEach(row => {
            const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const role = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            let match = false;

            if (searchType === 'email') {
                match = email.includes(searchTerm);
            } else if (searchType === 'role') {
                match = role.includes(searchTerm);
            }

            if (match) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    function handleRoleChange(event) {
    const select = event.target;
    const userId = select.dataset.userId;
    const newRole = select.value;

    fetch('../back/updateuserrole.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            id: userId,
            role: newRole,
            action: 'updateRole'
        }).toString()
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Assume response is JSON
    })
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Success!',
                `User role updated to ${newRole}`,
                'success'
            ).then(() => {
                // Si l'utilisateur modifié est l'utilisateur authentifié, rechargez la page
                if (userId === currentUserId) {
                    window.location.href = '../back/index.php';
                } else {
                    // Sinon, continuez normalement
                    location.reload();
                }
            });
        } else {
            Swal.fire(
                'Error!',
                data.message || 'Unknown error',
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Error updating user role:', error);
        Swal.fire(
            'Error!',
            'Error updating user role. Please check the console for more details.',
            'error'
        );
    });
}


// Fetch user data from users.php
fetch('/car_rent/back/affichusers.php')
    .then(response => response.json())
    .then(data => {
        users = data;
        const userTableBody = document.getElementById('user-table-body');

        users.forEach(user => {
            const row = document.createElement('tr');

            // Profile Picture
            const pdpCell = document.createElement('td');
            const pdpImg = document.createElement('img');
            pdpImg.src = user.pdp ? baseURL + user.pdp : 'assets/imgs/default-avatar.png';
            pdpImg.alt = 'Profile Picture';
            pdpImg.className = 'profile-pic'; // Apply the CSS class
            pdpCell.appendChild(pdpImg);
            row.appendChild(pdpCell);

            // Email
            const emailCell = document.createElement('td');
            emailCell.textContent = user.mail;
            row.appendChild(emailCell);

            // Role
            const roleCell = document.createElement('td');
            const roleSelect = document.createElement('select');
            roleSelect.className = 'role-select';
            roleSelect.dataset.userId = user.id;

            // Add role options
            ['User', 'Admin', 'Agent de location'].forEach(role => {
                const option = document.createElement('option');
                option.value = role;
                option.textContent = role;
                if (role === user.role) {
                    option.selected = true;
                }
                roleSelect.appendChild(option);
            });

            roleSelect.addEventListener('change', handleRoleChange);
            roleCell.appendChild(roleSelect);
            row.appendChild(roleCell);

            // Actions
            const actionsCell = document.createElement('td');

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.className = 'delete-btn';
            deleteButton.addEventListener('click', () => deleteUser(user.id)); // Call deleteUser function
            actionsCell.appendChild(deleteButton);
            row.appendChild(actionsCell);

            // Add the row to the table
            userTableBody.appendChild(row);
        });

        // Attach event listener to search input
        searchInput.addEventListener('input', filterTable);
        searchTypeSelect.addEventListener('change', filterTable);
    })
    .catch(error => console.error('Error fetching user data:', error));



    function deleteUser(userId) {
        if (!userId) {
            console.error('User ID is undefined or null');
            return;
        }

        console.log(`Attempting to delete user with ID: ${userId}`);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this user!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('../back/deleteuser.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        id: userId,
                        action: 'delete'
                    }).toString()
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Récupère la réponse en texte brut
                })
                .then(text => {
                    console.log('Response received:', text);
                    try {
                        const data = JSON.parse(text); // Tente de parser la réponse JSON
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                data.message || 'User deleted successfully',
                                'success'
                            ).then(() => {
                                location.reload(); // Recharge la page pour mettre à jour la liste des utilisateurs
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Unknown error',
                                'error'
                            );
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        Swal.fire(
                            'Error!',
                            'Failed to parse server response as JSON.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    Swal.fire(
                        'Error!',
                        'Error deleting user. Please check the console for more details.',
                        'error'
                    );
                });
            }
        });
    }
});

</script>

    </script>
    <style>
        .profile-pic {
        width: 100px; /* Adjust size as needed */
        height: 100px; /* Adjust size as needed */
        border-radius: 50%;
        object-fit: cover; /* Ensure image covers the area without distortion */
    }
        .delete-btn {
        background-color: red;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
    }

    .delete-btn:hover {
        background-color: darkred;
    }
    /* Style pour la zone de recherche */
.search {
    display: flex;
    align-items: center;
    margin: 20px;
    gap: 10px;
}
    .custom-select {
        background-color: #87CEEB; /* Bleu ciel */
        border: 1px solid #ADD8E6; /* Bordure en bleu clair */
        color: #000000; /* Texte en noir pour une bonne lisibilité */
        padding: 5px; /* Optionnel : ajoute du padding pour améliorer l'apparence */
    }
    .custom-select option {
        background-color: #87CEEB; /* Bleu ciel pour les options */
        color: #FFFFFF; /* Texte des options en blanc */
    }
.search label {
    display: flex;
    align-items: center;
    gap: 10px;
}

#search-type {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

#search-input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    width: 200px;
    transition: border-color 0.3s;
}

#search-input:focus {
    border-color: #3085d6;
    outline: none;
}

/* Style pour les icônes de recherche */
ion-icon[name="search-outline"] {
    color: #3085d6;
    cursor: pointer;
}

/* Style pour le tableau des utilisateurs */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f4f4f4;
    color: #333;
}

tr:hover {
    background-color: #f9f9f9;
}



    
    </style>
</body>
</html>