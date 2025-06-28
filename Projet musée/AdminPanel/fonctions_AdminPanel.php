<?php
function LoadBD()
{
    $host = "localhost";
    $dbname = "utilisateurs_db";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function LoadVisiteur($ordre, $date, $ID, $total, $searchField = null, $searchValue = null) {
    $pdo = LoadBD();

    $query = "SELECT * FROM visiteur";
    $conditions = [];
    $params = [];

    // Heure_Depart = 0
    if ($total === 'true') {
        $conditions[] = "Heure_Depart = 0";
    }

    // Recherche personnalisée
    $allowedFields = ['Nom', 'Prenom', 'ID_Visiteur', 'ID_Type_Entree'];
    if (!empty($searchField) && !empty($searchValue) && in_array($searchField, $allowedFields)) {
        if ($searchField === 'ID_Visiteur') {
            $conditions[] = "$searchField = ?";
            $params[] = $searchValue; // pas de %
        } else {
            $conditions[] = "$searchField LIKE ?";
            $params[] = "%$searchValue%";
        }
    }

    // Ajout de WHERE si nécessaire
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Ordre
    if ($ID === 'true') {
        $orderBy = 'ID_Visiteur';
    } elseif ($date === 'true') {
        $orderBy = 'Date_Visite';
    } else {
        $orderBy = 'Nom';
    }

    $orderDirection = ($ordre === 'true') ? 'ASC' : 'DESC';
    $query .= " ORDER BY $orderBy $orderDirection";

    // Préparation et exécution
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function adminPanel($user, $mdp, $role)
{
    $pdo = LoadBD();

    $sql = "INSERT IGNORE INTO Utilisateurs (Pseudo, MDP, permissions) VALUES (:user, :mdp, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user' => $user,
        ':mdp' => $mdp,
        ':role' => $role
    ]);
}

function visiteur($nom, $prenom, $Arrivee, $Depart, $date_envoi, $exposition)
{
    $pdo = LoadBD();
    $NbVisiteurs=statsActu();

    if ($NbVisiteurs<50){
        $sql = " INSERT INTO Visiteur (Nom,Prenom,Heure_Arrivee, Heure_Depart, Date_Visite, ID_Type_Entree) 
            VALUES (:nom, :prenom, :Arrivee, :Depart, :date_envoi, :exposition)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':Arrivee' => $Arrivee,
            ':Depart' => $Depart,
            ':date_envoi' => $date_envoi,
            ':exposition' => $exposition
        ]);
    }else{
        echo "Erreur";
    }
}

function depart($valeurBouton, $heure)
{
    $pdo = LoadBD();

    $sql = "UPDATE visiteur SET Heure_Depart = :heure WHERE ID_Visiteur = :id";
    $test = $pdo->prepare($sql);
    $test->execute([
        ':id' => $valeurBouton,
        ':heure' => $heure
    ]);
}

function departAll($heure)
{
    $pdo = LoadBD();

    $sql = "UPDATE visiteur SET Heure_Depart = :heure WHERE Heure_Depart = 0";
    $test = $pdo->prepare($sql);
    $test->execute([
        ':heure' => $heure
    ]);
}

function statsJour()
{
    $pdo = LoadBD();
    $query = "SELECT COUNT(*) FROM visiteur WHERE DATE(Date_Visite) = CURDATE();";

    $Result = $pdo->prepare($query);
    $Result->execute();

    $stats = $Result->fetchColumn();

    return (int)$stats;
}

function statsActu()
{
    $pdo = LoadBD();
    $query = "SELECT COUNT(*) FROM visiteur WHERE Heure_Depart = 0;";

    $Result = $pdo->prepare($query);
    $Result->execute();

    $stats = $Result->fetchColumn();

    return (int)$stats;
}


function statsTotal()
{
    $pdo = LoadBD();
    $query = "SELECT COUNT(*) FROM visiteur;";

    $Result = $pdo->prepare($query);
    $Result->execute();

    $stats = $Result->fetchColumn();

    return (int)$stats;
}

function LoadDiagBar($dateDebut, $dateFin) {
    $pdo = LoadBD();
    $barChartData = [];

    // Nettoyage : convertir '' en null
    $dateDebut = !empty($dateDebut) ? $dateDebut : null;
    $dateFin = !empty($dateFin) ? $dateFin : null;

    $query = "SELECT COUNT(*) FROM visiteur WHERE ID_Type_Entree = :typeEntree";

    // Ajout de la condition si les deux dates sont valides
    if (!empty($dateDebut) && !empty($dateFin)) {
        $query .= " AND Date_Visite >= :dateDebut AND Date_Visite <= :dateFin";
    }

    for ($type = 1; $type <= 3; $type++) {
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':typeEntree', $type, PDO::PARAM_INT);

        if (!empty($dateDebut) && !empty($dateFin)) {
            $stmt->bindValue(':dateDebut', $dateDebut);
            $stmt->bindValue(':dateFin', $dateFin);
        }

        $stmt->execute();
        $barChartData[] = (int)$stmt->fetchColumn();
    }

    return $barChartData;
}

function LoadDiagLigne() {
    $pdo = LoadBD();
    $currentDate = new DateTime();
    $dates = [];
    $lineChartData = [];

    // Ajouter le jour actuel
    $dates[] = $currentDate->format('Y-m-d');

    // Ajouter les six jours précédents
    for ($i = 1; $i <= 6; $i++) {
        $previousDate = clone $currentDate;
        $previousDate->sub(new DateInterval("P{$i}D"));
        $dates[] = $previousDate->format('Y-m-d');
    }

    $dates = array_reverse($dates);

    foreach ($dates as $date) {
        $query = "SELECT COUNT(*) FROM visiteur WHERE Date_Visite = :date";
        $Result = $pdo->prepare($query);
        $Result->execute([':date' => $date]);
        $Nb = $Result->fetchColumn();
        array_push($lineChartData, $Nb);
    }

    return $lineChartData;
}


function LoadDiagLigneName(){
    $currentDate = new DateTime();
    $dates = [];

    // jour actuel
    $dates[] = clone $currentDate;

    // Ajouter les six jours précédents
    for ($i = 1; $i <= 6; $i++) {
        $previousDate = clone $currentDate;
        $previousDate->sub(new DateInterval("P{$i}D"));
        $dates[] = $previousDate;
    }

    $nomsDesJours = [];

    // Formater les noms des jours en français
    foreach ($dates as $date) {
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );
        $nomsDesJours[] = $formatter->format($date);
    }

    return array_reverse($nomsDesJours);
}










