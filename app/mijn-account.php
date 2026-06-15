<?php
session_start();

// Niet ingelogd? Stuur naar login
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

require('db.php');

$gebruiker_id = $_SESSION['gebruiker_id'];
$melding = '';
$fout = '';

// Verwerk annulering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'annuleren') {
    $boeking_id = (int)($_POST['boeking_id'] ?? 0);

    if ($boeking_id > 0) {
        // Controleer of deze boeking van deze gebruiker is
        $stmt = $pdo->prepare("SELECT * FROM boekingen WHERE id = ? AND gebruiker_id = ? AND status = 'actief'");
        $stmt->execute([$boeking_id, $gebruiker_id]);
        $boeking = $stmt->fetch();

        if ($boeking) {
            try {
                // Zet status op geannuleerd
                $stmt2 = $pdo->prepare("UPDATE boekingen SET status = 'geannuleerd' WHERE id = ?");
                $stmt2->execute([$boeking_id]);

                // Zet de plekken terug vrij in de reizen tabel
                $stmt3 = $pdo->prepare("UPDATE reizen SET geboekt_personen = geboekt_personen - ? WHERE id = ?");
                $stmt3->execute([$boeking['aantal_personen'], $boeking['reis_id']]);

                $melding = 'Je boeking is geannuleerd.';
            } catch (PDOException $e) {
                $fout = 'Er ging iets mis bij het annuleren.';
            }
        } else {
            $fout = 'Boeking niet gevonden.';
        }
    }
}

// Haal gebruikersgegevens op
$stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE id = ?");
$stmt->execute([$gebruiker_id]);
$gebruiker = $stmt->fetch();

// Haal alle boekingen op van deze gebruiker
$stmt = $pdo->prepare("
    SELECT b.*, r.bestemming, r.type_reis, r.startdatum, r.einddatum, r.kleur
    FROM boekingen b
    JOIN reizen r ON b.reis_id = r.id
    WHERE b.gebruiker_id = ?
    ORDER BY b.aangemaakt_op DESC
");
$stmt->execute([$gebruiker_id]);
$boekingen = $stmt->fetchAll();

// Verdeel boekingen in actief en geannuleerd
$actieve_boekingen = [];
$geannuleerde_boekingen = [];
foreach ($boekingen as $boeking) {
    if ($boeking['status'] === 'actief') {
        $actieve_boekingen[] = $boeking;
    } else {
        $geannuleerde_boekingen[] = $boeking;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Mijn Account – Horizont Reizen</title>
</head>
<body>
    <?php require('includes/header.php'); ?>

    <section class="account-page">
        <div class="account-container">
            <h1 class="account-titel">Mijn Account</h1>

            <?php if ($melding): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($melding); ?></div>
            <?php endif; ?>
            <?php if ($fout): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($fout); ?></div>
            <?php endif; ?>

            <!-- Persoonlijke gegevens -->
            <div class="account-kaart">
                <h2>Persoonlijke gegevens</h2>
                <div class="account-gegevens">
                    <div class="gegeven-rij">
                        <span class="gegeven-label">Naam</span>
                        <span class="gegeven-waarde"><?php echo htmlspecialchars($gebruiker['voornaam'] . ' ' . $gebruiker['achternaam']); ?></span>
                    </div>
                    <div class="gegeven-rij">
                        <span class="gegeven-label">E-mailadres</span>
                        <span class="gegeven-waarde"><?php echo htmlspecialchars($gebruiker['email']); ?></span>
                    </div>
                    <div class="gegeven-rij">
                        <span class="gegeven-label">Lid sinds</span>
                        <span class="gegeven-waarde"><?php echo date('d-m-Y', strtotime($gebruiker['aangemaakt_op'])); ?></span>
                    </div>
                    <div class="gegeven-rij">
                        <span class="gegeven-label">Account type</span>
                        <span class="gegeven-waarde"><?php echo ucfirst($gebruiker['rol']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Actieve boekingen -->
            <div class="account-kaart">
                <h2>Mijn geboekte reizen (<?php echo count($actieve_boekingen); ?>)</h2>

                <?php if (count($actieve_boekingen) > 0): ?>
                    <div class="boekingen-lijst">
                        <?php foreach ($actieve_boekingen as $boeking): ?>
                            <?php $boeking_kleur = !empty($boeking['kleur']) ? $boeking['kleur'] : '#1b3a53'; ?>
                            <div class="boeking-kaart" style="border-left: 4px solid <?php echo htmlspecialchars($boeking_kleur); ?>;">
                                <div class="boeking-info">
                                    <h3><?php echo htmlspecialchars($boeking['bestemming']); ?></h3>
                                    <p class="boeking-type"><?php echo htmlspecialchars($boeking['type_reis']); ?></p>
                                    <p>📅 <?php echo date('d-m-Y', strtotime($boeking['startdatum'])); ?> → <?php echo date('d-m-Y', strtotime($boeking['einddatum'])); ?></p>
                                    <p>👥 <?php echo $boeking['aantal_personen']; ?> persoon<?php echo $boeking['aantal_personen'] > 1 ? 'en' : ''; ?></p>
                                    <p class="boeking-prijs">Totaal: <strong>€ <?php echo number_format($boeking['totaal_prijs'], 2, ',', '.'); ?></strong></p>
                                    <span class="status-badge status-actief">Actief</span>
                                </div>
                                <div class="boeking-acties">
                                    <a href="reis-detail.php?id=<?php echo $boeking['reis_id']; ?>" class="btn btn-secondary">Bekijk reis</a>
                                    <form method="POST" onsubmit="return confirm('Weet je zeker dat je deze boeking wilt annuleren?');">
                                        <input type="hidden" name="actie" value="annuleren">
                                        <input type="hidden" name="boeking_id" value="<?php echo $boeking['id']; ?>">
                                        <button type="submit" class="btn btn-danger">Annuleren</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="geen-boekingen">Je hebt nog geen reizen geboekt. <a href="reizen.php">Bekijk ons aanbod!</a></p>
                <?php endif; ?>
            </div>

            <!-- Geannuleerde boekingen -->
            <?php if (count($geannuleerde_boekingen) > 0): ?>
                <div class="account-kaart">
                    <h2>Geannuleerde boekingen</h2>
                    <div class="boekingen-lijst">
                        <?php foreach ($geannuleerde_boekingen as $boeking): ?>
                            <div class="boeking-kaart boeking-geannuleerd">
                                <div class="boeking-info">
                                    <h3><?php echo htmlspecialchars($boeking['bestemming']); ?></h3>
                                    <p>📅 <?php echo date('d-m-Y', strtotime($boeking['startdatum'])); ?> → <?php echo date('d-m-Y', strtotime($boeking['einddatum'])); ?></p>
                                    <p>👥 <?php echo $boeking['aantal_personen']; ?> persoon<?php echo $boeking['aantal_personen'] > 1 ? 'en' : ''; ?></p>
                                    <span class="status-badge status-geannuleerd">Geannuleerd</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['gebruiker_rol']) && $_SESSION['gebruiker_rol'] === 'admin'): ?>
                <div class="account-kaart">
                    <h2>Admin</h2>
                    <a href="admin/reizen.php" class="btn btn-primary">Ga naar admin panel</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
