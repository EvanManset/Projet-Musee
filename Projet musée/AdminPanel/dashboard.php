<?php
require("fonctions_AdminPanel.php");

$ordre = $_POST['state1'] ?? 'null';
$date = $_POST['state2'] ?? 'null';
$ID = $_POST['state3'] ?? 'null';
$total = $_POST['state4'] ?? 'null';

$nextState1 = ($ordre === 'null') ? 'true' : 'null';
$nextState2 = ($date === 'null') ? 'true' : 'null';
$nextState3 = ($ID === 'null') ? 'true' : 'null';
$nextState4 = ($total === 'null') ? 'true' : 'null';

$affichage1 = ($ordre === 'null') ? 'A/Z' : 'Z/A';
$affichage4 = ($total === 'null') ? 'Actuel' : 'Total';

// Bouton supp
if (isset($_POST['monBouton'])) {
    $valeurBouton = $_POST['monBouton'];
    $heure = date("Y-m-d H:i:s");

    depart($valeurBouton, $heure);
}

// Bouton supp all
if (isset($_POST['SupAll'])) {
    $heure = date("Y-m-d H:i:s");
    departAll($heure);
}

// 🔹 Traitement du formulaire Admin
if (isset($_POST['submit_admin'])) {
    $user = $_POST['user'] ?? null;
    $mdp = $_POST['mdp'] ?? null;
    $role = $_POST['role'] ?? null;
    adminPanel($user, $mdp, $role);

}

// 🔹 Traitement du formulaire Visiteur
if (isset($_POST['submit_visiteur'])) {
    $nom = $_POST['nom'] ?? null;
    $prenom = $_POST['prenom'] ?? null;
    $exposition = $_POST['exposition'] ?? null;
    $date_envoi = date("Y-m-d H:i:s");
    $Arrivee = date("Y-m-d H:i:s");
    $Depart = "";

    visiteur($nom, $prenom, $Arrivee, $Depart, $date_envoi, $exposition);
}

$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;

$dateDebut = !empty($dateDebut) ? $dateDebut : null;
$dateFin = !empty($dateFin) ? $dateFin : null;


$barChartLabels = ['Entrée Permanente', 'Entrée Temporaire', 'Entrée Permanente & Temporaire'];
$barChartData = LoadDiagBar($dateDebut, $dateFin);

$lineChartLabels = LoadDiagLigneName();
$lineChartData = LoadDiagLigne();


$StatsTotal = statsTotal();
$StatsActu = statsActu();
$StatsJour = statsJour();

$searchField = $_POST['search-field'] ?? null;
$searchValue = $_POST['search-value'] ?? null;

$visiteurs = LoadVisiteur($nextState1, $nextState2, $nextState3, $nextState4, $searchField, $searchValue);
?>
<script>
    var barChartLabels = <?php echo json_encode($barChartLabels); ?>;
    var barChartData = <?php echo json_encode($barChartData); ?>;

    var lineChartLabels = <?php echo json_encode($lineChartLabels); ?>;
    var lineChartData = <?php echo json_encode($lineChartData); ?>;
</script>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../styles/style_Dashboard.css">

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Inclure les scripts des graphiques APRÈS la déclaration des variables -->
    <script src="chart1.js"></script>
    <script src="chart2.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<ul class="navigation">

    <li data-link="home">
        <a href="#home">
            <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
            <span class="text">Acceuil</span>
        </a>
    </li>

    <li data-link="statistiques">
        <a href="#statistiques">
            <span class="icon"><ion-icon name="stats-chart-outline"></ion-icon></span>
            <span class="text">Statistiques</span>
        </a>
    </li>

    <li data-link="visiteurs">
        <a href="#visiteurs">
            <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
            <span class="text">Visteurs</span>
        </a>
    </li>

    <li data-link="">
        <a href="../index.php">
            <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
            <span class="text">Déconnection</span>
        </a>
    </li>
</ul>
</body>

<section class="home" id="home">
    <div class="container">
        <div class="entree">
            <!-- Premier formulaire -->
            <div class="content">
                <h2>Ajout d'un utilisateur</h2>
                <form method="POST" action="dashboard.php#home">
                    <div class="inputBx">
                        <label for="user"></label>
                        <input type="text" id="user" name="user" required>
                        <i>Identifiant</i>
                    </div>
                    <div class="inputBx">
                        <label for="mdp"></label>
                        <input type="password" id="mdp" name="mdp" required>
                        <i>Mot de passe</i>
                    </div>

                    <div class="inputBx">
                        <label for="role">Role</label>
                        <select name="role" id="role" required>
                            <option value="Utilisateur">Utilisateur</option>
                            <option value="Admin">Administrateur</option>
                        </select>
                    </div>

                    <div class="inputBx">
                        <input type="submit" value="Valider" name="submit_admin">
                    </div>
                </form>
            </div>
        </div>

        <div class="entree">
            <!-- Deuxième formulaire -->
            <div class="content">
                <h2>Sélection d'un Ticket</h2>
                <form method="POST" action="dashboard.php#home">
                    <div class="inputBx">
                        <label for="nom"></label>
                        <input type="text" id="nom" name="nom" required>
                        <i>Nom</i>
                    </div>
                    <div class="inputBx">
                        <label for="prenom"></label>
                        <input type="text" id="prenom" name="prenom" required>
                        <i>Prénom</i>
                    </div>
                    <div class="inputBx">
                        <label for="exposition">Type d'exposition</label>
                        <select name="exposition" id="exposition" required>
                            <option value="1">Permanente</option>
                            <option value="2">Temporaire</option>
                            <option value="3">Les Deux</option>
                        </select>
                    </div>

                    <div class="inputBx">
                        <input type="submit" value="Valider" name="submit_visiteur">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="statistiques" id="statistiques">
    <section class="stats" id="statistiques">
        <h2>Statistiques</h2>

        <form method="POST" action="#statistiques" class="form-container">
            <div class="input-group">
                <div class="inputBx">
                    <label for="dateDebut"></label>
                    <input type="date" id="dateDebut" name="dateDebut">
                    <i>Date début</i>
                </div>

                <div class="inputBx">
                    <label for="dateFin"></label>
                    <input type="date" id="dateFin" name="dateFin">
                    <i>Date fin</i>
                </div>
            </div>

            <button type="submit" class="submit-button">Soumettre</button>
        </form>

        <!-- Graphiques -->
        <div class="graphique" id="statistiques">
            <div class="container">
                <div class="chart">
                    <canvas id="barchart" width="500" height="500"></canvas>
                </div>
                <div class="chart">
                    <canvas id="linechart" width="500" height="500"></canvas>
                </div>
            </div>
        </div>

        <div class="stats-container">
            <div class="stats-box">
                <i class='bx bxs-calendar'></i>
                <h3>Visiteurs aujourd'hui</h3>
                <p><?php echo $StatsJour; ?></p>
            </div>
            <div class="stats-box">
                <i class='bx bx-check'></i>
                <h3>Visiteurs actuels</h3>
                <p><?php echo $StatsActu; ?>/50</p>
            </div>
            <div class="stats-box">
                <i class='bx bx-bar-chart-alt'></i>
                <h3>Visiteurs totaux</h3>
                <p><?php echo $StatsTotal; ?></p>
            </div>
        </div>
    </section>
</section>



<section class="visiteurs" id="visiteurs">
    <h2 class="heading">Les visiteurs</h2>

    <div class="recherche">
        <div class="container-stats recherche-box-horizontal">
            <div class="recherche-group">
                <form method="post" action="dashboard.php#visiteurs">
                    <label for="search-field">Rechercher par :</label>
                    <select class="search-field" name="search-field" id="search-field">
                        <option value="Nom">Nom</option>
                        <option value="Prenom">Prénom</option>
                        <option value="ID_Visiteur">ID</option>
                        <option value="ID_Type_Entree">Type d'exposition</option>
                    </select>
                    <input type="text" id="search-value" name="search-value" placeholder="Valeur de recherche"/>

                    <input type="hidden" name="state1" value="<?php echo $ordre; ?>">
                    <input type="hidden" name="state2" value="<?php echo $date; ?>">
                    <input type="hidden" name="state3" value="<?php echo $ID; ?>">
                    <input type="hidden" name="state4" value="<?php echo $total; ?>">

                    <input type="submit" value="Chercher" name="recherche" class="recherche-submit"/>
                </form>


                <form method="post" action="dashboard.php#visiteurs">
                    <button type="submit" name="toggleButton1" class="button <?php echo $ordre; ?>">
                        <?php echo $affichage1 ?>
                    </button>
                    <input type="hidden" name="state1" value="<?php echo $nextState1; ?>">
                    <input type="hidden" name="state2" value="<?php echo $date; ?>">
                    <input type="hidden" name="state3" value="<?php echo $ID; ?>">
                    <input type="hidden" name="state4" value="<?php echo $total; ?>">
                </form>

                <form method="post" action="dashboard.php#visiteurs">
                    <button type="submit" name="toggleButton3" class="button <?php echo $ID; ?>">
                        ID
                    </button>
                    <input type="hidden" name="state1" value="<?php echo $ordre; ?>">
                    <input type="hidden" name="state3" value="<?php echo $nextState3; ?>">
                    <input type="hidden" name="state4" value="<?php echo $total; ?>">
                </form>

                <form method="post" action="dashboard.php#visiteurs">
                    <button type="submit" name="toggleButton2" class="button <?php echo $date; ?>">
                        Date
                    </button>
                    <input type="hidden" name="state1" value="<?php echo $ordre; ?>">
                    <input type="hidden" name="state2" value="<?php echo $nextState2; ?>">
                    <input type="hidden" name="state4" value="<?php echo $total; ?>">
                </form>

                <form method="post" action="dashboard.php#visiteurs">
                    <button type="submit" name="toggleButton4" class="button <?php echo $total; ?>">
                        <?php echo $affichage4; ?>
                    </button>
                    <input type="hidden" name="state1" value="<?php echo $ordre; ?>">
                    <input type="hidden" name="state2" value="<?php echo $date; ?>">
                    <input type="hidden" name="state3" value="<?php echo $ID; ?>">
                    <input type="hidden" name="state4" value="<?php echo $nextState4; ?>">
                </form>
                <form method="post" action="dashboard.php#visiteurs">
                    <input type="submit" value="Tout supprimer" name="SupAll" class="recherche-submit"/>
                </form>
            </div>
        </div>
    </div>

    <div class="portfolio-container">
        <?php
        foreach ($visiteurs as $row) {
            ?>
            <div class="portfolio-box">
                <h4>Visiteur N°<?= $row['ID_Visiteur'] ?></h4>
                <img src="img/test.png" alt="">
                <div class="portfolio-layer">
                    <p></p>
                    <p>Mr/Mme : <?= $row['Nom'] ?>, <?= $row['Prenom'] ?><br>
                        Arrivée : <?= $row['Heure_Arrivee'] ?><br>
                        Type d'entrée : <?= $row['ID_Type_Entree'] ?>
                    </p>
                    <form action="dashboard.php#visiteurs" method="post">
                        <button type="submit" name="monBouton" value="<?= $row['ID_Visiteur'] ?>">Supprimer</button>
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navItems = document.querySelectorAll('.navigation li');
        const sections = document.querySelectorAll('section');

        // Active manuellement au clic
        navItems.forEach((item) => {
            item.addEventListener('click', () => {
                navItems.forEach((el) => el.classList.remove('active'));
                item.classList.add('active');
            });
        });

        // Active automatiquement en scroll
        window.addEventListener('scroll', () => {
            let scrollPosition = window.scrollY + 100;

            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');

                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    navItems.forEach((item) => {
                        item.classList.remove('active');
                        if (item.dataset.link === sectionId) {
                            item.classList.add('active');
                        }
                    });
                }
            });
        });
    });


    // =======================================================================
    // Ajouter la classe appropriée aux boutons après le chargement de la page
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.statistiques .button');
        buttons.forEach(function (button) {
            const currentState = button.classList.contains('true') ? 'true' : 'null';
            button.classList.add(currentState);
        });
    });
</script>
</html>



