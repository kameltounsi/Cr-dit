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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cars - List</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="/car_rent/img/top-logo1.png" type="image/png">
</head>

<body>
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

        <!-- Main Content -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
                    <label>
                        <input type="text" id="search-input" placeholder="Search...">
                        <select id="search-type" class="custom-select">
                            <option value="nom">Search by Name</option>
                            <option value="model">Search by Model</option>
                            <option value="ville">Search by City</option>
                        </select>
                    </label>
                </div>
                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>
    <!-- Add Car Button -->
    <div class="addCarBtn">
    <button class="chicButton" onclick="openModal()">Add a Car</button>
</div>

<style>
    .chicButton {
        background-color: #007bff; /* Primary color */
        color: white; /* Text color */
        padding: 12px 24px; /* Padding for button */
        font-size: 16px; /* Font size */
        font-weight: bold; /* Bold text */
        border: none; /* Remove default border */
        border-radius: 25px; /* Rounded corners */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow */
        cursor: pointer; /* Pointer cursor on hover */
        transition: all 0.3s ease; /* Smooth transition on hover */
        left: 15px;
    }

    .chicButton:hover {
        background-color: #0056b3; /* Darker shade on hover */
        transform: translateY(-2px); /* Slight upward movement on hover */
        box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
    }

    .chicButton:active {
        background-color: #004085; /* Even darker shade on click */
        transform: translateY(0); /* Return to normal position on click */
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Restore shadow on click */
    }

    .chicButton:focus {
        outline: none; /* Remove focus outline */
        box-shadow: 0px 0px 0px 4px rgba(0, 123, 255, 0.4); /* Focus shadow */
    }
</style>

            <!-- Cars Table -->
            <div class="details">
                <div class="carTable">
                    <div class="cardHeader">
                        <h2>Car List</h2>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Model</th>
                                <th>Image 1</th>
                                <th>Image 2</th>
                                <th>Image 3</th>
                                <th>City</th>
                                <th>Price</th> <!-- New column for actions -->
                                <th>Actions</th> <!-- New column for actions -->

                            </tr>
                        </thead>
                        <tbody id="car-table-body">
                            <!-- Table rows will be generated dynamically by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<!-- Modal pour Modifier une Voiture -->
<div id="editCarModal" class="modal">
    <div class="modal-content">
        <span class="closeBtn" onclick="closeEditModal()">&times;</span>
        <h2>Edit Car</h2>
        <form id="editCarForm" action="edit_car.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="editCarId" name="car_id">

            <label for="editNom">Car Name:</label>
            <input type="text" id="editNom" name="nom" required>

            <label for="editModel">Car Model:</label>
            <select id="editModel" name="model" required>
                <!-- États-Unis -->
                <option value="Ford">Ford</option>
                <option value="Chevrolet">Chevrolet</option>
                <option value="Tesla">Tesla</option>
                <option value="Cadillac">Cadillac</option>
                <option value="Dodge">Dodge</option>
                <option value="Jeep">Jeep</option>
                <option value="Buick">Buick</option>
                <option value="Chrysler">Chrysler</option>

                <!-- France -->
                <option value="Renault">Renault</option>
                <option value="Peugeot">Peugeot</option>
                <option value="Citroën">Citroën</option>
                <option value="DS Automobiles">DS Automobiles</option>
                <option value="Bugatti">Bugatti</option>

                <!-- Allemagne -->
                <option value="Mercedes-Benz">Mercedes-Benz</option>
                <option value="BMW">BMW</option>
                <option value="Audi">Audi</option>
                <option value="Volkswagen">Volkswagen</option>
                <option value="Porsche">Porsche</option>
                <option value="Opel">Opel</option>

                <!-- Japon -->
                <option value="Toyota">Toyota</option>
                <option value="Honda">Honda</option>
                <option value="Nissan">Nissan</option>
                <option value="Mazda">Mazda</option>
                <option value="Subaru">Subaru</option>
                <option value="Mitsubishi">Mitsubishi</option>
                <option value="Suzuki">Suzuki</option>
                <option value="Lexus">Lexus</option>

                <!-- Ajouter d'autres marques de voitures au besoin -->
            </select>

            <label for="editImg1">Image 1:</label>
            <input type="file" id="editImg1" name="img1" accept="image/*">

            <label for="editImg2">Image 2:</label>
            <input type="file" id="editImg2" name="img2" accept="image/*">

            <label for="editImg3">Image 3:</label>
            <input type="file" id="editImg3" name="img3" accept="image/*">

            <label for="editVille">Ville:</label>
            <select id="editVille" name="ville" required>
                <option value="">Sélectionner une ville</option>
                <option value="Tunis">Tunis</option>
                <option value="Ariana">Ariana</option>
                <option value="Ben Arous">Ben Arous</option>
                <option value="Manouba">Manouba</option>
                <option value="Nabeul">Nabeul</option>
                <option value="Zaghouan">Zaghouan</option>
                <option value="Bizerte">Bizerte</option>
                <option value="Béja">Béja</option>
                <option value="Jendouba">Jendouba</option>
                <option value="Kef">Kef</option>
                <option value="Siliana">Siliana</option>
                <option value="Sousse">Sousse</option>
                <option value="Monastir">Monastir</option>
                <option value="Mahdia">Mahdia</option>
                <option value="Sfax">Sfax</option>
                <option value="Kairouan">Kairouan</option>
                <option value="Kasserine">Kasserine</option>
                <option value="Sidi Bouzid">Sidi Bouzid</option>
                <option value="Gabès">Gabès</option>
                <option value="Mednine">Mednine</option>
                <option value="Tataouine">Tataouine</option>
                <option value="Gafsa">Gafsa</option>
                <option value="Tozeur">Tozeur</option>
                <option value="Kebili">Kebili</option>
            </select>

            <!-- Champ pour le prix -->
            <label for="editPrix">Prix:</label>
            <input type="number" id="editPrix" name="prix" step="0.01" required>

            <!-- Ajouter de l'espace entre le prix et le bouton -->
            <div style="margin-top: 15px;">
                <button id="editCarButton" type="submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<script>
function openEditModal(carId, carName, carModel, carVille, carPrix, img1, img2, img3) {
    // Remplir les champs du formulaire avec les valeurs existantes
    document.getElementById("editCarId").value = carId;
    document.getElementById("editNom").value = carName;
    document.getElementById("editModel").value = carModel;
    document.getElementById("editVille").value = carVille;
    document.getElementById("editPrix").value = carPrix;

    // Optionnel: Charger les images existantes si nécessaire (vous pouvez afficher les images actuelles dans le modal)
    // document.getElementById("editImg1Preview").src = img1;
    // document.getElementById("editImg2Preview").src = img2;
    // document.getElementById("editImg3Preview").src = img3;

    // Afficher le modal de modification
    document.getElementById("editCarModal").style.display = "block";
}

function closeEditModal() {
    // Fermer le modal de modification
    document.getElementById("editCarModal").style.display = "none";
}

// Écouter l'événement de soumission du formulaire
document.getElementById("editCarForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche l'envoi habituel du formulaire

    // Afficher une confirmation avant de soumettre le formulaire
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to save these changes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, save it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Si l'utilisateur confirme, soumettre le formulaire avec fetch
            const formData = new FormData(this); // Récupère les données du formulaire

            fetch("edit_car.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text()) // Ou .json() si votre serveur retourne du JSON
            .then(result => {
                // Afficher une notification de succès
                Swal.fire({
                    title: 'Success!',
                    text: 'Car updated successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Recharge la page pour afficher les modifications
                    closeEditModal(); // Ferme le modal après mise à jour
                });
            })
            .catch(error => {
                // Afficher une notification d'erreur en cas d'échec
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error updating the car. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
});

</script>
<!-- Modal for Adding a Car -->
<div id="carModal" class="modal">
    <div class="modal-content">
        <span class="closeBtn" onclick="closeModal()">&times;</span>
        <h2>Add a Car</h2>
        <form id="carForm" action="add_car.php" method="POST" enctype="multipart/form-data">
            <label for="nom">Car Name:</label>
            <input type="text" id="nom" name="nom" required>

            <label for="model">Car Model:</label>
            <select id="model" name="model" required>
                <!-- États-Unis -->
                <option value="Ford">Ford</option>
                <option value="Chevrolet">Chevrolet</option>
                <option value="Tesla">Tesla</option>
                <option value="Cadillac">Cadillac</option>
                <option value="Dodge">Dodge</option>
                <option value="Jeep">Jeep</option>
                <option value="Buick">Buick</option>
                <option value="Chrysler">Chrysler</option>

                <!-- France -->
                <option value="Renault">Renault</option>
                <option value="Peugeot">Peugeot</option>
                <option value="Citroën">Citroën</option>
                <option value="DS Automobiles">DS Automobiles</option>
                <option value="Bugatti">Bugatti</option>

                <!-- Allemagne -->
                <option value="Mercedes-Benz">Mercedes-Benz</option>
                <option value="BMW">BMW</option>
                <option value="Audi">Audi</option>
                <option value="Volkswagen">Volkswagen</option>
                <option value="Porsche">Porsche</option>
                <option value="Opel">Opel</option>

                <!-- Japon -->
                <option value="Toyota">Toyota</option>
                <option value="Honda">Honda</option>
                <option value="Nissan">Nissan</option>
                <option value="Mazda">Mazda</option>
                <option value="Subaru">Subaru</option>
                <option value="Mitsubishi">Mitsubishi</option>
                <option value="Suzuki">Suzuki</option>
                <option value="Lexus">Lexus</option>

                <!-- Add more car brands as needed -->
            </select>

            <label for="img1">Image 1:</label>
            <input type="file" id="img1" name="img1" accept="image/*" required>

            <label for="img2">Image 2:</label>
            <input type="file" id="img2" name="img2" accept="image/*">

            <label for="img3">Image 3:</label>
            <input type="file" id="img3" name="img3" accept="image/*">

            <label for="ville">Ville:</label>
            <select id="ville" name="ville" required>
                <option value="">Sélectionner une ville</option>
                <option value="Tunis">Tunis</option>
                <option value="Ariana">Ariana</option>
                <option value="Ben Arous">Ben Arous</option>
                <option value="Manouba">Manouba</option>
                <option value="Nabeul">Nabeul</option>
                <option value="Zaghouan">Zaghouan</option>
                <option value="Bizerte">Bizerte</option>
                <option value="Béja">Béja</option>
                <option value="Jendouba">Jendouba</option>
                <option value="Kef">Kef</option>
                <option value="Siliana">Siliana</option>
                <option value="Sousse">Sousse</option>
                <option value="Monastir">Monastir</option>
                <option value="Mahdia">Mahdia</option>
                <option value="Sfax">Sfax</option>
                <option value="Kairouan">Kairouan</option>
                <option value="Kasserine">Kasserine</option>
                <option value="Sidi Bouzid">Sidi Bouzid</option>
                <option value="Gabès">Gabès</option>
                <option value="Mednine">Mednine</option>
                <option value="Tataouine">Tataouine</option>
                <option value="Gafsa">Gafsa</option>
                <option value="Tozeur">Tozeur</option>
                <option value="Kebili">Kebili</option>
            </select>

            <!-- Nouveau champ pour le prix -->
            <label for="prix">Prix:</label>
            <input type="number" id="prix" name="prix" step="0.01" required>

            <!-- Ajouter de l'espace entre le prix et le bouton -->
            <div style="margin-top: 15px;">
                <button id="addCarButton" type="submit">Add</button>
            </div>
        </form>
    </div>
</div>


    <script>
        function deleteCar(carId) {
    if (!carId) {
        console.error('Car ID is undefined or null');
        return;
    }
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this car!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../back/delete_car.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    id: carId,
                    action: 'delete'
                }).toString()
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Get the response as JSON
            })
            .then(data => {
                console.log('Response received:', data);
                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        data.message || 'Car deleted successfully',
                        'success'
                    ).then(() => {
                        location.reload(); // Reload the page to update the list
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
                console.error('Error deleting car:', error);
                Swal.fire(
                    'Error!',
                    'Error deleting car. Please check the console for more details.',
                    'error'
                );
            });
        }
    });
}
        </script>
        <script>
document.getElementById("carForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche l'envoi habituel du formulaire

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to add this car?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, add it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData(this); // Récupère les données du formulaire
            
            fetch("add_car.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text()) // Ou .json() si votre serveur retourne du JSON
            .then(result => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Car added successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Ferme le modal et recharge la page
                    location.reload(); 
                    document.getElementById("carForm").reset(); // Réinitialise le formulaire
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while adding the car.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: 'Cancelled',
                text: 'The car was not added.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    });
});



        </script>
    <!-- Script for dynamic data and modal -->
    <script>
        const baseURL = '../';  // Adjusted path to go up one directory level

        document.addEventListener('DOMContentLoaded', () => {
            const searchTypeSelect = document.getElementById('search-type');
            const searchInput = document.getElementById('search-input');
            const carTableBody = document.getElementById('car-table-body');

            let cars = [];

            // Function to filter table rows
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const searchType = searchTypeSelect.value;

                carTableBody.querySelectorAll('tr').forEach(row => {
                    const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const model = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const city = row.querySelector('td:nth-child(6)').textContent.toLowerCase();

                    let match = false;

                    if (searchType === 'nom') {
                        match = name.includes(searchTerm);
                    } else if (searchType === 'model') {
                        match = model.includes(searchTerm);
                    } else if (searchType === 'ville') {
                        match = city.includes(searchTerm);
                    }

                    if (match) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
// Function to delete car
            // Fetch car data from the server
            fetch('/car_rent/back/affichcars.php')
    .then(response => response.json())
    .then(data => {
        cars = data;
        carTableBody.innerHTML = '';  // Vide le corps du tableau avant de le remplir à nouveau

        cars.forEach(car => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${car.nom}</td>
                <td>${car.model}</td>
                <td><img src="${car.img1}" alt="Image 1" style="width: 100px;"></td>
                <td><img src="${car.img2}" alt="Image 2" style="width: 100px;"></td>
                <td><img src="${car.img3}" alt="Image 3" style="width: 100px;"></td>
                <td>${car.ville}</td>
                <td>${car.prix}</td>
                <td>
<button class="editBtn" onclick="openEditModal(${car.id}, '${car.nom}', '${car.model}', '${car.ville}', ${car.prix}, '${car.img1}', '${car.img2}', '${car.img3}')">Edit</button>
                    <button class="deleteBtn" onclick="deleteCar(${car.id})">Delete</button>
                </td>
            `;

            carTableBody.appendChild(row);
        });
    })
    .catch(error => console.error('Error fetching car data:', error));

// Event listener for search input and type change
searchInput.addEventListener('input', filterTable);
searchTypeSelect.addEventListener('change', filterTable);

        });

        // Function to open modal for adding a car
        function openModal() {
            document.getElementById('carModal').style.display = 'block';
        }

        // Function to close modal
        function closeModal() {
            document.getElementById('carModal').style.display = 'none';
        }

        // Placeholder function for editing a car
        function editCar(carId) {
            // Logic for editing car will go here
            alert('Edit car with ID: ' + carId);
        }
    </script>

    <!-- Include ionicons for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>

</body>
<style>
    /* Style for car images */
.car-img {
    width: 100px; /* Set the width of the images */
    height: auto; /* Maintain aspect ratio */
    max-height: 80px; /* Optional: set a max height */
    object-fit: cover; /* Optional: cover the area without distortion */
    border-radius: 4px; /* Optional: add rounded corners */
    display: block; /* Ensure images are displayed as blocks */
}

    </style>
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
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
    backdrop-filter: blur(5px); /* Add blur to the background */
}

/* Modal Content/Box */
.modal-content {
    background-color: #fff;
    margin: 10% auto; /* Centered vertically */
    padding: 30px;
    border-radius: 15px;
    width: 40%; /* Adjust based on content */
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
    animation: slide-down 0.4s ease-in-out; /* Slide down effect */
}

/* Animation for modal appearance */
@keyframes slide-down {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* The Close Button */
.closeBtn {
    color: #333;
    float: right;
    font-size: 24px;
    font-weight: bold;
    transition: color 0.3s;
}

.closeBtn:hover,
.closeBtn:focus {
    color: #f44336;
    text-decoration: none;
    cursor: pointer;
}

/* Styling the form labels and inputs */
.modal-content label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #555;
}

.modal-content input[type="text"],
.modal-content select,
.modal-content input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

/* Styling the submit button */
.modal-content button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.modal-content button[type="submit"]:hover {
    background-color: #45a049;
}

/* Add some hover effect to the submit button */
.modal-content button[type="submit"]:hover {
    background-color: #45a049;
}

/* Add some margin to the modal content */
.modal-content h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
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
</html>
