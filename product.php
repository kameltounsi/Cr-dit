<?php
session_start(); // Assurez-vous que la session est démarrée
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_mail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BMCAUTO - CARS</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/global.css" rel="stylesheet">
	<link href="css/product.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
	<link href="css/ken-burns.css" rel="stylesheet" type="text/css" media="all" />
	<link type="text/css" rel="stylesheet" href="css/animate.css">
	<link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
	<script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" href="img/top-logo1.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="product.css">

  </head>
  
<body>
    <section id="header" class="clearfix cd-secondary-nav">
        <div class="container-fluid">
         <div class="row">
          <div class="col-sm-12">
            <nav class="navbar navbar-default">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                   </button>
                   <a href="index.html">
                      <img src="img/top-logo.png" alt="BMCAUTO Logo" style="height: 70px; width: 400px;">
                   </a>
                </div>
       
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                   <ul class="nav navbar-nav">
                      <li><a class="m_tag" href="index.html">Home</a></li>
                      <li><a class="m_tag active_m" href="product.php">Voitures</a></li>
                      <li><a class="m_tag" href="listepaiements.php">Liste des paiements</a></li>
                   </ul>
                   
                  <!-- Right aligned section for Sign Up and Sign In -->
                  <ul class="nav navbar-nav navbar-right" id="auth-buttons">
                    <!-- Sign Up Link -->
                    <li><a class="m_tag" href="#" data-toggle="modal" data-target="#registerModal">Sign Up</a></li>
                
                    <!-- Sign In Link -->
                    <li><a class="m_tag" href="#" data-toggle="modal" data-target="#loginModal">Sign In</a></li>
                </ul>
                <!-- User Avatar with Dropdown Menu -->
    <div id="user-avatar" class="navbar-right" style="display: none;">
        <div id="avatarContainer" style="position: relative; display: inline-block;">
            <img id="profileImage" src="path/to/default-avatar.jpg" alt="User Avatar" class="img-circle" width="50" height="50" style="cursor: pointer;">
            <!-- Dropdown Menu -->
            <div id="dropdownMenu" class="dropdown-menu" style="display: none; position: absolute; top: 100%; right: 0; background-color: #fff; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.15); z-index: 1000;">
                <a href="#" id="modifyProfileLink" class="dropdown-item">Modify Profile</a>
                <a href="#" id="logoutLink" class="dropdown-item">Logout</a>
            </div>
        </div>
    </div>
     <!-- Modal for Sign Up -->
    <div id="registerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="registerModalLabel">Sign Up</h4>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="registerController.php" accept-charset="UTF-8" id="register-form" enctype="multipart/form-data">
                        <div class="form-group text-center">
                            <label for="profilePhoto" class="d-block">Profile Photo</label>
                            <div id="profilePhotoContainer" class="profile-photo-circle">
                                <img id="profilePhotoPreview" src="#" alt="Profile Photo Preview" style="display:none;">
                                <input type="file" class="form-control-file" id="profilePhoto" name="profilePhoto" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="registerEmail">Email address</label>
                            <input type="email" class="form-control" id="registerEmail" name="registerEmail" placeholder="Email address" required="">
                            <span class="error-message" id="emailError"></span>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="registerPassword" name="registerPassword" placeholder="Password" required="">
                                <span class="input-group-addon">
                                    <i class="fa fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                            <span class="error-message" id="passwordError"></span>
                            <small id="passwordStrength" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required="">
                                <span class="input-group-addon">
                                    <i class="fa fa-eye" id="toggleConfirmPassword"></i>
                                </span>
                            </div>
                            <span class="error-message" id="confirmPasswordError"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" id="registerSubmit">Register</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <p>Already have an account? <a href="#" data-toggle="modal" data-target="#loginModal" data-dismiss="modal">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript for handling form submission and email uniqueness -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('register-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            console.log('Login button clicked');
    
            // Clear previous error messages
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';
            document.getElementById('confirmPasswordError').textContent = '';
    
            // Get form elements
            var email = document.getElementById('registerEmail').value;
            var password = document.getElementById('registerPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
    
            // Email validation regex
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
            var valid = true;
    
            // Email validation
            if (!emailPattern.test(email)) {
                document.getElementById('emailError').textContent = 'Please enter a valid email address.';
                valid = false;
            }
    
            // Password strength validation
            if (!isStrongPassword(password)) {
                document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.';
                valid = false;
            } else {
                document.getElementById('passwordError').textContent = '';
            }
    
            // Passwords match validation
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
                valid = false;
            }
    
            if (valid) {
                // Check if email is unique
                fetch('checkEmail.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.emailExists) {
                        Swal.fire({
                            title: 'Email Already Used!',
                            text: 'The email address you entered is already in use. Please choose another one.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // If email is unique, submit the form
                        var formData = new FormData(this);
                        
                        fetch('registerController.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    document.getElementById('register-form').reset();
                                    $('#registerModal').modal('hide'); // Close the modal
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred while checking the email.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    
        // Optional: Live validation on input change
        document.getElementById('registerEmail').addEventListener('input', function() {
            var email = this.value;
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('emailError').textContent = 'Invalid email address.';
            } else {
                document.getElementById('emailError').textContent = '';
            }
        });
    
        document.getElementById('registerPassword').addEventListener('input', function() {
            var password = this.value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            } else {
                document.getElementById('confirmPasswordError').textContent = '';
            }
            // Check password strength and update UI
            var strength = getPasswordStrength(password);
            document.getElementById('passwordStrength').textContent = strength.message;
            document.getElementById('passwordStrength').style.color = strength.color;
        });
    
        document.getElementById('confirmPassword').addEventListener('input', function() {
            var confirmPassword = this.value;
            var password = document.getElementById('registerPassword').value;
            if (confirmPassword !== password) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
            } else {
                document.getElementById('confirmPasswordError').textContent = '';
            }
        });
    
        // Password strength check function
        function isStrongPassword(password) {
            var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,])[A-Za-z\d@$!%*?&.,]{8,}$/;
            return pattern.test(password);
        }
    
        // Password strength message function
        function getPasswordStrength(password) {
            var strength = { message: '', color: '' };
            if (password.length < 8) {
                strength.message = 'Password too short.';
                strength.color = 'red';
            } else if (!/[a-z]/.test(password)) {
                strength.message = 'Include at least one lowercase letter.';
                strength.color = 'orange';
            } else if (!/[A-Z]/.test(password)) {
                strength.message = 'Include at least one uppercase letter.';
                strength.color = 'orange';
            } else if (!/\d/.test(password)) {
                strength.message = 'Include at least one digit.';
                strength.color = 'orange';
            } else if (!/[@$!%*?&,.]/.test(password)) {
                strength.message = 'Include at least one special character.';
                strength.color = 'orange';
            } else {
                strength.message = 'Password is strong.';
                strength.color = 'green';
            }
            return strength;
        }
    });
    </script>
    
    <!-- Modal for Sign In -->
    <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="loginModalLabel">Sign In</h4>
                </div>
                <div class="modal-body">
                    <form class="form" id="login-nav">
                        <div class="form-group">
                            <label class="sr-only" for="loginEmail">Email address</label>
                            <input type="email" class="form-control" id="loginEmail" placeholder="Email address" required>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="loginPassword">Password</label>
                            <input type="password" class="form-control" id="loginPassword" placeholder="Password" required>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Remember me
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-block">Sign In</button>
                        </div>
                    </form>
                    <div id="loginErrorMessage" style="color: red; display: none;"></div>
                </div>
                <div class="modal-footer">
                    <p>Don't have an account? <a href="#" data-toggle="modal" data-target="#registerModal" data-dismiss="modal">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check session status when the page loads
            function checkSession() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'check_session.php', true); // PHP script to check session
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'connected') {
                            displayAvatar(response.user.pdp);
                            if (response.user.role === 'Admin') {
                                window.location.href = 'back/index.php';
                            }
                        }
                    }
                };
                xhr.send();
            }
        
            checkSession(); // Call the function when the page loads
        
            // Handle form submission for login
            document.getElementById('login-nav').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent normal form submission
        
                // Get email and password field values
                var email = document.getElementById('loginEmail').value;
                var password = document.getElementById('loginPassword').value;
        
                // Send data via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'login.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            if (response.user.role === 'Admin') {
                                window.location.href = 'back/index.php';
                            } else {
                                displayAvatar(response.user.pdp);
                                fetchCarsData();
                                $('#loginModal').modal('hide'); // Close the login modal
                            }
                        } else {
                            displayLoginError(response.message);
                        }
                    } else {
                        displayLoginError('An error occurred while logging in.');
                    }
                };
                xhr.send(JSON.stringify({
                    email: email,
                    password: password
                }));
            });
        
            // Function to display the avatar after a successful login
            function displayAvatar(userProfileImage) {
                document.getElementById('auth-buttons').style.display = 'none';
                document.getElementById('user-avatar').style.display = 'block';
                document.getElementById('profileImage').src = userProfileImage || 'path/to/default-avatar.jpg';
            }
        
            // Display an error message if login fails
            function displayLoginError(message) {
                var errorMsg = document.getElementById('loginErrorMessage');
                errorMsg.textContent = message;
                errorMsg.style.display = 'block';
            }
        
            // Handle profile image click to show/hide dropdown menu
            document.getElementById('profileImage').addEventListener('click', function() {
                var dropdownMenu = document.getElementById('dropdownMenu');
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });
        
            // Hide dropdown menu if clicking outside of the avatar container
            document.addEventListener('click', function(event) {
                if (!document.getElementById('avatarContainer').contains(event.target)) {
                    document.getElementById('dropdownMenu').style.display = 'none';
                }
            });
        
            // Handle Logout button click
            document.getElementById('logoutLink').addEventListener('click', function(event) {
                event.preventDefault();
        
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to log out?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, log out!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform logout on the server side
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'logout.php', true); // Server-side logout script
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // Reset the interface after logout
                                document.getElementById('auth-buttons').style.display = 'block';
                                document.getElementById('user-avatar').style.display = 'none';
                                document.getElementById('profileImage').src = 'path/to/default-avatar.jpg';

                            }
                        };
                        xhr.send();
                        window.location.href = 'index.html'; // Redirect to index.html

                    }
                });
            });
        });
        </script>
      
      <!-- Add JavaScript for previewing the uploaded image -->
      <script>
        document.getElementById('profilePhoto').addEventListener('change', function(event) {
          var reader = new FileReader();
          reader.onload = function(e) {
            var img = document.getElementById('profilePhotoPreview');
            img.src = e.target.result;
            img.style.display = 'block';
          };
          reader.readAsDataURL(event.target.files[0]);
        });
      document.getElementById('togglePassword').addEventListener('click', function() {
        var passwordField = document.getElementById('registerPassword');
        var type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.classList.toggle('fa-eye-slash');
      });
    
      document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        var confirmPasswordField = document.getElementById('confirmPassword');
        var type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        this.classList.toggle('fa-eye-slash');
      });
      </script>
      
       </section>

  
    <!-- Modal for Modify Profile -->
    <div id="modifyProfileModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modifyProfileModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modifyProfileModalLabel">Modify Profile</h4>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="modifyProfile.php" accept-charset="UTF-8" id="modify-profile-form" enctype="multipart/form-data">
                        <div class="form-group text-center">
                            <label for="profilePhoto" class="d-block">Profile Photo</label>
                            <div id="profilePhotoContainer" class="profile-photo-circle">
                                <img id="profilePhotoPreview" alt="Profile Photo Preview" class="img-circle" width="100" height="100" style="display:none;">
                                <input type="file" class="form-control-file" id="profilePhoto" name="profilePhoto" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="modifyEmail">Email address</label>
                            <input type="email" class="form-control" id="modifyEmail" name="modifyEmail" placeholder="Email address" required="">
                            <span class="error-message" id="modifyEmailError"></span>
                        </div>
                        <div class="form-group">
                            <label for="modifyPassword">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="modifyPassword" name="modifyPassword" placeholder="Password">
                                <span class="input-group-addon">
                                    <i class="fa fa-eye" id="toggleModifyPassword"></i>
                                </span>
                            </div>
                            <span class="error-message" id="modifyPasswordError"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirmModifyPassword">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmModifyPassword" name="confirmModifyPassword" placeholder="Confirm Password">
                                <span class="input-group-addon">
                                    <i class="fa fa-eye" id="toggleConfirmModifyPassword"></i>
                                </span>
                            </div>
                            <span class="error-message" id="confirmModifyPasswordError"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" id="modifyProfileSubmit">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du clic sur le bouton "Modify Profile"
        document.getElementById('modifyProfileLink').addEventListener('click', function(event) {
            event.preventDefault();
    
            // Fetch user data using an AJAX call
            fetch('getUserData.php')
                .then(response => response.json())
                .then(user => {
                    if (user.status === 'success') {
                        const profilePhotoPreview = document.getElementById('profilePhotoPreview');
                        
                        if (user.data.profilePhoto) {
                            profilePhotoPreview.src = user.data.profilePhoto;
                            profilePhotoPreview.style.display = 'block';
                        } else {
                            profilePhotoPreview.style.display = 'none';
                        }
                        
                        document.getElementById('modifyEmail').value = user.data.email;
    
                        // Display the modal
                        $('#modifyProfileModal').modal('show');
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: user.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        });
    
        // Gestion de la soumission du formulaire de modification du profil
        document.getElementById('modify-profile-form').addEventListener('submit', function(event) {
            // Votre logique existante pour soumettre le formulaire
        });
    });
    </script>
    </section>

<section id="product_main">
 <div class="container">
  <div class="row">
   <div class="product_main_1">
    <h2>CARS</h2>
	<p><a href="index.html"> HOME </a> <i class="fa fa-angle-double-right"></i> <a href="#"> CARS </a></p>
   </div>
  </div>
 </div>
</section>

<div class="search-form">
    <select id="searchOption">
        <option value="nom">Nom</option>
        <option value="model">Modèle</option>
        <option value="ville">Ville</option>
    </select>
    <input type="text" id="searchInput" placeholder="Recherchez...">
    <input type="number" id="minPrice" placeholder="Prix Min">
    <input type="number" id="maxPrice" placeholder="Prix Max">
</div>

<div class="car-grid" id="carGrid">
    <!-- Les voitures seront affichées ici -->
</div>





<script>
document.getElementById('searchInput').addEventListener('input', performDynamicSearch);
document.getElementById('searchOption').addEventListener('change', performDynamicSearch);
document.getElementById('minPrice').addEventListener('input', performDynamicSearch);
document.getElementById('maxPrice').addEventListener('input', performDynamicSearch);

let allCars = [];

function checkSession() {
  fetch('check_session.php', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'connected') {
      fetchCarsData();
    } else {
      $('#loginModal').modal('show');
    }
  })
  .catch(error => {
    console.error('Erreur:', error);
  });
}

function fetchCarsData() {
  fetch('affichcars.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(cars => {
    allCars = cars;
    displayCars(allCars);
  })
  .catch(error => {
    console.error('Erreur:', error);
  });
}

function displayCars(cars) {
  const carGrid = document.getElementById('carGrid');
  carGrid.innerHTML = '';

  if (cars.length === 0) {
    carGrid.innerHTML = '<p>Aucune voiture trouvée.</p>';
    return;
  }

  cars.forEach(car => {
    const carItem = document.createElement('div');
    carItem.className = 'car-item';

    const imgContainer = document.createElement('div');
    imgContainer.className = 'img-container';

    const img = document.createElement('img');
    img.src = car.img1.replace('../', '');
    imgContainer.appendChild(img);

    const nextButton = document.createElement('button');
    nextButton.textContent = 'Next';
    nextButton.className = 'next-button';

    const prevButton = document.createElement('button');
    prevButton.textContent = 'Previous';
    prevButton.className = 'prev-button';

    let currentImageIndex = 0;
    const images = [car.img1, car.img2, car.img3]
      .filter(image => image && image.trim() !== '')
      .map(img => img.replace('../', ''));

    if (images.length > 0) {
      img.src = images[currentImageIndex];

      nextButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        img.src = images[currentImageIndex];
      });

      prevButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        img.src = images[currentImageIndex];
      });
    } else {
      imgContainer.innerHTML = "<p>Aucune image disponible</p>";
    }

    imgContainer.appendChild(prevButton);
    imgContainer.appendChild(nextButton);

    const carName = document.createElement('h3');
    carName.textContent = car.nom + ' ' + car.model;

    const carCity = document.createElement('p');
    carCity.textContent = 'Ville: ' + car.ville;

    const carPrice = document.createElement('p');
    carPrice.className = 'price';
    carPrice.textContent = 'Prix: ' + car.prix + ' DT';

    const reserveButton = document.createElement('button');
    reserveButton.textContent = 'Réserver';
    reserveButton.className = 'reserve-button';
    reserveButton.addEventListener('click', () => {
  openReservationModal(car); // Open modal for the specific car
});
;

    carItem.appendChild(imgContainer);
    carItem.appendChild(carName);
    carItem.appendChild(carCity);
    carItem.appendChild(carPrice);
    carItem.appendChild(reserveButton);

    carGrid.appendChild(carItem);
  });
}


function performDynamicSearch() {
  const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();
  const searchOption = document.getElementById('searchOption').value;
  const minPrice = parseFloat(document.getElementById('minPrice').value);
  const maxPrice = parseFloat(document.getElementById('maxPrice').value);

  const filteredCars = allCars.filter(car => {
    // Filtrage par critère sélectionné
    let matchesSearch = false;
    if (searchOption === 'nom') {
      matchesSearch = car.nom.toLowerCase().includes(searchInput);
    } else if (searchOption === 'model') {
      matchesSearch = car.model.toLowerCase().includes(searchInput);
    } else if (searchOption === 'ville') {
      matchesSearch = car.ville.toLowerCase().includes(searchInput);
    }

    // Filtrage par prix
    let matchesPrice = true;
    if (!isNaN(minPrice) && car.prix < minPrice) {
      matchesPrice = false;
    }
    if (!isNaN(maxPrice) && car.prix > maxPrice) {
      matchesPrice = false;
    }

    return matchesSearch && matchesPrice;
  });

  displayCars(filteredCars);
}

document.addEventListener('DOMContentLoaded', checkSession);





// Appeler cette fonction lors d'une connexion réussie
function onLoginSuccess() {
  $('#loginModal').modal('hide'); // Fermer le modal de connexion
  fetchCarsData(); // Charger les voitures
}

</script>
<div id="reservationModal" class="modal" style="display: none;">
    <div class="modal-content">
    <span id="modalCloseButton" class="close">&times;</span>
    <h5 style="text-align: center; font-size: 1.5rem;">Réservation de voiture</h5>
        <form id="reservationForm">
            <input type="hidden" id="userId" value="<?php echo $user_id; ?>">
            <input type="hidden" id="carId">
            <input type="hidden" id="prixUnitaire"> <!-- Ajout du champ caché pour le prix unitaire -->
            <div>
                <label for="dateDebut">Date de début</label>
                <input type="date" id="dateDebut" required>
                <span id="errorDateDebut" class="error-message"></span>
            </div>
            <div>
                <label for="dateFin">Date de fin</label>
                <input type="date" id="dateFin" required>
                <span id="errorDateFin" class="error-message"></span>
            </div>
            <div>
                <label for="prixTotal">Prix total</label>
                <input type="text" id="prixTotal" class="form-control" readonly>
            </div>
            <div>
                <label for="telephone">Téléphone</label>
                <input type="text" id="telephone" required>
                <span id="errorTelephone" class="error-message"></span>
            </div>
            <div>
                <label for="mail">Email</label>
                <input type="email" id="mail" value="<?php echo $user_mail; ?>" required>
                <span id="errorMail" class="error-message"></span>
            </div>
            <button type="submit">Confirmer la réservation</button>
        </form>

    </div>
</div>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.getElementById('reservationForm').addEventListener('submit', submitReservationForm);
document.getElementById('dateDebut').addEventListener('change', updatePrixTotal);
document.getElementById('dateFin').addEventListener('change', updatePrixTotal);

function openReservationModal(car) {
    // Assurez-vous que le champ prixUnitaire est défini ici
    document.getElementById('carId').value = car.id;
    document.getElementById('prixUnitaire').value = car.prix;
    document.getElementById('reservationModal').style.display = 'block';
}

function calculerNombreJours(dateDebut, dateFin) {
    const debut = new Date(dateDebut);
    const fin = new Date(dateFin);
    const differenceTemps = fin - debut;
    return Math.max(differenceTemps / (1000 * 3600 * 24), 0);
}

function updatePrixTotal() {
    const dateDebut = document.getElementById('dateDebut').value;
    const dateFin = document.getElementById('dateFin').value;
    const prixUnitaire = parseFloat(document.getElementById('prixUnitaire').value);

    if (dateDebut && dateFin && prixUnitaire) {
        const nombreJours = calculerNombreJours(dateDebut, dateFin);
        const prixTotal = nombreJours * prixUnitaire;
        document.getElementById('prixTotal').value = `${prixTotal.toFixed(2)} DT`;
    } else {
        document.getElementById('prixTotal').value = '0.00 DT';
    }
}

function validateForm() {
    clearErrorMessages();

    const dateDebut = document.getElementById('dateDebut').value;
    const dateFin = document.getElementById('dateFin').value;
    const telephone = document.getElementById('telephone').value;
    const mail = document.getElementById('mail').value;
    const currentDate = new Date().toISOString().split('T')[0];
    let isValid = true;

    if (!dateDebut || !dateFin) {
        showError('errorDateDebut', 'Les dates de début et de fin sont obligatoires.');
        showError('errorDateFin', 'Les dates de début et de fin sont obligatoires.');
        isValid = false;
    }

    if (dateDebut < currentDate || dateFin < currentDate) {
        showError('errorDateDebut', 'Les dates de début et de fin doivent être supérieures ou égales à la date actuelle.');
        showError('errorDateFin', 'Les dates de début et de fin doivent être supérieures ou égales à la date actuelle.');
        isValid = false;
    }

    if (dateFin <= dateDebut) {
        showError('errorDateDebut', 'La date de fin doit être supérieure à la date de début.');
        isValid = false;
    }

    const phoneRegex = /^[0-9]{8}$/;
    if (!phoneRegex.test(telephone)) {
        showError('errorTelephone', 'Le numéro de téléphone doit contenir exactement 8 chiffres.');
        isValid = false;
    }

    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(mail)) {
        showError('errorMail', 'Veuillez entrer une adresse email valide.');
        isValid = false;
    }

    return isValid;
}

function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
    }
}

function clearErrorMessages() {
    document.querySelectorAll('.error-message').forEach(span => {
        span.textContent = '';
    });
}

function submitReservationForm(event) {
    event.preventDefault();

    if (!validateForm()) {
        return;
    }

    const dateDebut = document.getElementById('dateDebut').value;
    const dateFin = document.getElementById('dateFin').value;
    const carId = document.getElementById('carId').value;

    // Vérifier la disponibilité de la voiture
    fetch('checkAvailability.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ carId, dateDebut, dateFin })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Disponibilité:', data); // Ajoutez ce log pour vérifier la réponse
        if (!data.available) {
            Swal.fire('Erreur', 'La voiture est déjà réservée pour ces dates.', 'error');
        } else {
            // Si la voiture est disponible, soumettre la réservation
            const reservationData = {
                id_user: document.getElementById('userId').value,
                id_car: document.getElementById('carId').value,
                date_current: new Date().toISOString().split('T')[0]+ 'Z',
                date_debut: document.getElementById('dateDebut').value,
                date_fin: document.getElementById('dateFin').value,
                prixtotal: document.getElementById('prixTotal').value.split(' ')[0],
                telephone: document.getElementById('telephone').value,
                mail: document.getElementById('mail').value
            };

            fetch('reserver.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reservationData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Réservation:', data); // Ajoutez ce log pour vérifier la réponse
                if (data.success) {
                    Swal.fire('Réservation confirmée', 'Votre réservation a été confirmée avec succès.', 'success');
                    closeModal();
                } else {
                    Swal.fire('Erreur', 'Une erreur est survenue lors de la réservation.', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur réservation:', error);
            });
        }
    })
    .catch(error => {
        console.error('Erreur disponibilité:', error);
    });
}



function closeModal() {
    document.getElementById('reservationModal').style.display = 'none';
}

document.getElementById('modalCloseButton').addEventListener('click', closeModal);

window.addEventListener('click', (event) => {
    const modal = document.getElementById('reservationModal');
    if (event.target === modal) {
        closeModal();
    }
});
</script>
<style>


</style>
</body>
 </html>