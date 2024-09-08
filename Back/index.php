<?php
session_start();
require_once 'Model/Config.php'; // Inclure la configuration de la base de données

// Supposons que vous stockez les rôles dans une session après la connexion de l'utilisateur
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'Admin' && $user_role !== 'Agent de location') {
    // Redirigez l'utilisateur vers la page d'accueil ou une page d'erreur
    header("Location: ../index.html");
    exit;
}
function getUserCountByRole($role) {
    try {
        $pdo = GetConnexion();
        $stmt = $pdo->prepare("SELECT COUNT(*) as user_count FROM users WHERE role = :role");
        $stmt->execute(['role' => $role]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_count'] ?? 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}
function getAdminAndAgentCount() {
    try {
        $pdo = GetConnexion();
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE role IN ('Admin', 'Agent de location')");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}
function getVoitureCount() {
    try {
        $pdo = GetConnexion();
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM voitures");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}
// Fonction pour obtenir le total des prixtotal dans la table reservation
function getTotalPrixReservation() {
    try {
        $pdo = GetConnexion();
        $stmt = $pdo->prepare("SELECT SUM(prixtotal) as total FROM reservation");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

$totalPrixReservation = round(getTotalPrixReservation());


// Obtenir le nombre de voitures
$voitureCount = getVoitureCount();
// Obtenir le nombre d'administrateurs et d'agents de location
$adminAgentCount = getAdminAndAgentCount();

// Obtenir le nombre d'utilisateurs avec le rôle "user"
$userCount = getUserCountByRole('user');
function getVoitureCountByVille() {
    try {
        $pdo = GetConnexion();
        $stmt = $pdo->prepare("SELECT ville, COUNT(*) as voiture_count FROM voitures GROUP BY ville");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
$voitureCountByVille = getVoitureCountByVille();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMCAUTO - Back</title>
    <link rel="icon" href="/car_rent/img/top-logo1.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="logo-car"></ion-icon>
                        </span>
                        <span class="title">BMC Auto</span>
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
                        <a href="View/customers.php">
                            <span class="icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </span>
                            <span class="title">Customers</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($user_role === 'Agent de location') { ?>
                    <li>
                        <a href="View/voitures.php">
                            <span class="icon">
                                <ion-icon name="car-sport-outline"></ion-icon>
                            </span>
                            <span class="title">Cars</span>
                        </a>
                    </li>

                    <li>
                        <a href="View/reservation_back.php">
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
    <script src="../assets/js/main.js"></script>
<script>
document.getElementById('signOutLink').addEventListener('click', function(event) {
    event.preventDefault(); // Empêche le comportement par défaut du lien
    
    // Effectuer la requête de déconnexion
    fetch('Controller/logout1.php')
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url; // Redirige vers la page de connexion ou autre
            }
        })
        .catch(error => console.error('Erreur lors de la déconnexion:', error));
});

</script>

<!-- ====== ionicons ======= -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script>fetch('Controller/logout.php', {
    method: 'POST',
})
.then(response => response.text()) // Changez temporairement en text pour déboguer
.then(data => {
    console.log(data); // Affichez la réponse complète dans la console
    try {
        const jsonData = JSON.parse(data);
        if (jsonData.status === 'success') {
            window.location.href = '../index.html';
        } else {
            console.error('Logout failed');
        }
    } catch (e) {
        console.error('Error parsing JSON:', e);
    }
})
.catch(error => console.error('Error:', error));
</script>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="user">
    <img src="<?php echo htmlspecialchars( ($_SESSION['user_pdp'] ?? 'assets/imgs/default_profile.jpg')); ?>" alt="User Profile">
</div>


            </div>

            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                    <div class="numbers"><?php echo htmlspecialchars($userCount); ?></div>
                    <div class="cardName">Users</div>
                    </div>

                    <div class="iconBx">
                    <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
    <div>
        <div class="numbers"><?php echo htmlspecialchars($adminAgentCount); ?></div>
        <div class="cardName">Admins & Agents</div>
    </div>

    <div class="iconBx">
        <ion-icon name="person-outline"></ion-icon>
    </div>
</div>


<div class="card">
    <div>
        <div class="numbers"><?php echo htmlspecialchars($voitureCount); ?></div>
        <div class="cardName">Cars</div>
    </div>

    <div class="iconBx">
        <ion-icon name="car-outline"></ion-icon>
    </div>
</div>
<div class="card">
    <div>
        <div class="numbers">$<?php echo htmlspecialchars(number_format($totalPrixReservation, 2)); ?></div>
        <div class="cardName">Total Earning</div>
    </div>

    <div class="iconBx">
        <ion-icon name="cash-outline"></ion-icon>
    </div>
</div>
<div class="card1">
    <div class="cardName">Cars by City</div>
    <canvas id="voitureChart"></canvas>
</div>
<style>
    .card1 {
        width: 100%; /* Ajustez la largeur selon vos besoins */
        height: 100%;
        margin: 20px auto; /* Centrer horizontalement avec un espacement supérieur et inférieur */
        padding: 20px; /* Espacement intérieur pour un peu de padding */
        border: 1px solid #ccc; /* Bordure légère pour mieux délimiter la carte */
        border-radius: 8px; /* Coins arrondis */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Ombre légère pour effet de profondeur */
        background-color: #fff; /* Couleur de fond blanche */
    }

    #voitureChart {
        width: 100% !important; /* Assurez-vous que le canvas prend toute la largeur de la carte */
        height: 800px; /* Hauteur souhaitée pour le graphique */
    }
</style>

<?php
// Préparez les données pour le graphique
$voitureCountByVille = getVoitureCountByVille();
$labels = [];
$data = [];

foreach ($voitureCountByVille as $villeData) {
    $labels[] = $villeData['ville'];
    $data[] = $villeData['voiture_count'];
}

$chartData = [
    'labels' => $labels,
    'data' => $data
];

$chartDataJson = json_encode($chartData);

?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('voitureChart').getContext('2d');
        const chartData = <?php echo $chartDataJson; ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Number of Cars',
                    data: chartData.data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>


    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>