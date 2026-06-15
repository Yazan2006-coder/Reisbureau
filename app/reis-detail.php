<?php
session_start();
require('db.php');

// Haal de reis ID op uit de URL
$reis_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Als er geen id is, stuur terug naar reizen pagina
if ($reis_id == 0) {
    header('Location: reizen.php');
    exit;
}

// Haal de reis op uit de database
$stmt = $pdo->prepare("SELECT * FROM reizen WHERE id = ? AND actief = TRUE");
$stmt->execute([$reis_id]);
$reis = $stmt->fetch();

// Reis niet gevonden? Terug naar reizen
if (!$reis) {
    header('Location: reizen.php');
    exit;
}

$melding = '';
$fout = '';

// Verwerk boeking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'boeken') {
    // Controleer of gebruiker ingelogd is
    if (!isset($_SESSION['gebruiker_id'])) {
        header('Location: login.php');
        exit;
    }

    $aantal_personen = (int)($_POST['aantal_personen'] ?? 1);
    $gebruiker_id = $_SESSION['gebruiker_id'];

    // Validatie
    if ($aantal_personen < 1 || $aantal_personen > 10) {
        $fout = 'Kies een geldig aantal personen (1-10).';
    } else {
        // Controleer beschikbaarheid
        $vrije_plaatsen = $reis['max_personen'] - $reis['geboekt_personen'];
        if ($aantal_personen > $vrije_plaatsen) {
            $fout = 'Er zijn niet genoeg plaatsen beschikbaar. Nog ' . $vrije_plaatsen . ' plekken vrij.';
        } else {
            $totaal_prijs = $reis['prijs'] * $aantal_personen;

            try {
                // Boeking opslaan
                $stmt = $pdo->prepare("INSERT INTO boekingen (gebruiker_id, reis_id, aantal_personen, totaal_prijs) VALUES (?, ?, ?, ?)");
                $stmt->execute([$gebruiker_id, $reis_id, $aantal_personen, $totaal_prijs]);

                // Update geboekte personen in reizen tabel
                $stmt2 = $pdo->prepare("UPDATE reizen SET geboekt_personen = geboekt_personen + ? WHERE id = ?");
                $stmt2->execute([$aantal_personen, $reis_id]);

                $melding = 'Gefeliciteerd! Je hebt ' . $aantal_personen . ' plek(ken) geboekt voor ' . htmlspecialchars($reis['bestemming']) . '. Totaalprijs: € ' . number_format($totaal_prijs, 2, ',', '.');

                // Herlaad de reis zodat we de nieuwe beschikbaarheid zien
                $stmt3 = $pdo->prepare("SELECT * FROM reizen WHERE id = ?");
                $stmt3->execute([$reis_id]);
                $reis = $stmt3->fetch();
            } catch (PDOException $e) {
                $fout = 'Er ging iets mis bij het boeken. Probeer het opnieuw.';
            }
        }
    }
}

// Verwerk recensie plaatsen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'recensie') {
    if (!isset($_SESSION['gebruiker_id'])) {
        header('Location: login.php');
        exit;
    }

    $beoordeling = (int)($_POST['beoordeling'] ?? 0);
    $tekst = trim($_POST['tekst'] ?? '');
    $gebruiker_id = $_SESSION['gebruiker_id'];

    if ($beoordeling < 1 || $beoordeling > 5) {
        $fout = 'Kies een beoordeling van 1 tot 5 sterren.';
    } elseif (empty($tekst)) {
        $fout = 'Schrijf een tekst bij je recensie.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO recensies (gebruiker_id, reis_id, beoordeling, tekst) VALUES (?, ?, ?, ?)");
            $stmt->execute([$gebruiker_id, $reis_id, $beoordeling, $tekst]);
            $melding = 'Je recensie is geplaatst!';
        } catch (PDOException $e) {
            $fout = 'Er ging iets mis bij het plaatsen van je recensie.';
        }
    }
}

// Haal alle recensies op voor deze reis
$stmt = $pdo->prepare("SELECT r.*, g.voornaam, g.achternaam FROM recensies r JOIN gebruikers g ON r.gebruiker_id = g.id WHERE r.reis_id = ? ORDER BY r.aangemaakt_op DESC");
$stmt->execute([$reis_id]);
$recensies = $stmt->fetchAll();

// Bereken gemiddelde beoordeling
$gemiddelde = 0;
if (count($recensies) > 0) {
    $totaal = 0;
    foreach ($recensies as $r) {
        $totaal += $r['beoordeling'];
    }
    $gemiddelde = round($totaal / count($recensies), 1);
}

// Bereken aantal nachten
$start = new DateTime($reis['startdatum']);
$eind = new DateTime($reis['einddatum']);
$interval = $start->diff($eind);
$aantal_nachten = $interval->days;

$vrije_plaatsen = $reis['max_personen'] - $reis['geboekt_personen'];

// Kleur met standaardwaarde als die leeg is
$kleur = !empty($reis['kleur']) ? $reis['kleur'] : '#1b3a53';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title><?php echo htmlspecialchars($reis['bestemming']); ?> – Horizont Reizen</title>
</head>
<body>
    <?php require('includes/header.php'); ?>

    <section class="detail-hero" style="background-color: <?php echo htmlspecialchars($kleur); ?>;">
        <div class="detail-hero-content">
            <a href="reizen.php" class="detail-terug">← Terug naar reizen</a>
            <span class="tag tag-default"><?php echo htmlspecialchars($reis['type_reis']); ?></span>
            <h1><?php echo htmlspecialchars($reis['bestemming']); ?></h1>
            <p><?php echo $aantal_nachten; ?> nachten · vanaf € <?php echo number_format($reis['prijs'], 2, ',', '.'); ?> p.p.</p>
        </div>
    </section>

    <section class="detail-main">
        <div class="detail-container">

            <!-- Linker kolom: info + reviews -->
            <div class="detail-left">

                <?php if ($melding): ?>
                    <div class="alert alert-success"><?php echo $melding; ?></div>
                <?php endif; ?>
                <?php if ($fout): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($fout); ?></div>
                <?php endif; ?>

                <div class="detail-info-kaart">
                    <h2>Over deze reis</h2>
                    <p><?php echo htmlspecialchars($reis['beschrijving']); ?></p>

                    <div class="detail-feiten">
                        <div class="detail-feit">
                            <strong>Vertrek</strong>
                            <span><?php echo date('d-m-Y', strtotime($reis['startdatum'])); ?></span>
                        </div>
                        <div class="detail-feit">
                            <strong>Terug</strong>
                            <span><?php echo date('d-m-Y', strtotime($reis['einddatum'])); ?></span>
                        </div>
                        <div class="detail-feit">
                            <strong>Duur</strong>
                            <span><?php echo $aantal_nachten; ?> nachten</span>
                        </div>
                        <div class="detail-feit">
                            <strong>Vrije plaatsen</strong>
                            <span><?php echo $vrije_plaatsen; ?> van <?php echo $reis['max_personen']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Recensies lezen -->
                <div class="recensies-sectie">
                    <h2>Recensies
                        <?php if (count($recensies) > 0): ?>
                            <span class="recensie-gemiddelde">⭐ <?php echo $gemiddelde; ?> / 5 (<?php echo count($recensies); ?> recensies)</span>
                        <?php endif; ?>
                    </h2>

                    <?php if (count($recensies) > 0): ?>
                        <div class="recensies-lijst">
                            <?php foreach ($recensies as $recensie): ?>
                                <div class="recensie-kaart">
                                    <div class="recensie-header">
                                        <strong><?php echo htmlspecialchars($recensie['voornaam'] . ' ' . $recensie['achternaam']); ?></strong>
                                        <span class="recensie-sterren">
                                            <?php
                                            // Toon sterren
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $recensie['beoordeling'] ? '★' : '☆';
                                            }
                                            ?>
                                        </span>
                                        <span class="recensie-datum"><?php echo date('d-m-Y', strtotime($recensie['aangemaakt_op'])); ?></span>
                                    </div>
                                    <p><?php echo htmlspecialchars($recensie['tekst']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="geen-recensies">Er zijn nog geen recensies voor deze reis. Wees de eerste!</p>
                    <?php endif; ?>

                    <!-- Recensie plaatsen -->
                    <?php if (isset($_SESSION['gebruiker_id'])): ?>
                        <div class="recensie-formulier">
                            <h3>Jouw recensie plaatsen</h3>
                            <form method="POST" id="recensie-form">
                                <input type="hidden" name="actie" value="recensie">

                                <div class="form-group">
                                    <label>Beoordeling</label>
                                    <div class="ster-keuze" id="ster-keuze">
                                        <span data-waarde="1">★</span>
                                        <span data-waarde="2">★</span>
                                        <span data-waarde="3">★</span>
                                        <span data-waarde="4">★</span>
                                        <span data-waarde="5">★</span>
                                    </div>
                                    <input type="hidden" name="beoordeling" id="beoordeling-waarde" value="0">
                                </div>

                                <div class="form-group">
                                    <label for="tekst">Je recensie</label>
                                    <textarea id="tekst" name="tekst" rows="4" placeholder="Schrijf hier je ervaring..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Recensie plaatsen</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <p class="login-prompt"><a href="login.php">Log in</a> om een recensie te plaatsen.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rechter kolom: boekingsformulier -->
            <div class="detail-right">
                <div class="boeken-kaart">
                    <div class="boeken-prijs">
                        <span>vanaf</span>
                        <strong>€ <?php echo number_format($reis['prijs'], 2, ',', '.'); ?></strong>
                        <span>per persoon</span>
                    </div>

                    <?php if ($vrije_plaatsen > 0): ?>
                        <?php if (isset($_SESSION['gebruiker_id'])): ?>
                            <form method="POST" id="boek-form">
                                <input type="hidden" name="actie" value="boeken">

                                <div class="form-group">
                                    <label for="aantal_personen">Aantal personen</label>
                                    <select name="aantal_personen" id="aantal_personen">
                                        <?php for ($i = 1; $i <= min(10, $vrije_plaatsen); $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?> persoon<?php echo $i > 1 ? 'en' : ''; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div class="totaal-prijs-preview" id="totaal-preview">
                                    Totaal: <strong id="totaal-bedrag">€ <?php echo number_format($reis['prijs'], 2, ',', '.'); ?></strong>
                                </div>

                                <button type="submit" class="btn btn-primary boeken-btn">Boek nu</button>
                            </form>
                        <?php else: ?>
                            <p>Je moet ingelogd zijn om te boeken.</p>
                            <a href="login.php" class="btn btn-primary">Inloggen om te boeken</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="volzet-melding">
                            <p>X Deze reis is helaas volzet.</p>
                        </div>
                    <?php endif; ?>

                    <div class="boeken-info">
                        <p>✓ Gratis annuleren binnen 24 uur</p>
                        <p>✓ Veilig betalen</p>
                        <p>✓ ANVR & SGR gecertificeerd</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require('includes/footer.php'); ?>

    <script>
        // Bereken de totaalprijs live
        var prijsPerPersoon = <?php echo $reis['prijs']; ?>;

        var aantalSelect = document.getElementById('aantal_personen');
        var totaalBedrag = document.getElementById('totaal-bedrag');

        if (aantalSelect) {
            aantalSelect.addEventListener('change', function() {
                var aantal = parseInt(this.value);
                var totaal = aantal * prijsPerPersoon;
                totaalBedrag.textContent = '€ ' + totaal.toFixed(2).replace('.', ',');
            });
        }

        // Sterren klikken voor recensie
        var sterren = document.querySelectorAll('#ster-keuze span');
        var beoordelingInput = document.getElementById('beoordeling-waarde');

        sterren.forEach(function(ster) {
            ster.addEventListener('click', function() {
                var waarde = parseInt(this.getAttribute('data-waarde'));
                beoordelingInput.value = waarde;

                // Kleur de sterren
                sterren.forEach(function(s, index) {
                    if (index < waarde) {
                        s.style.color = '#f39c12';
                    } else {
                        s.style.color = '#ccc';
                    }
                });
            });

            // Hover effect
            ster.addEventListener('mouseover', function() {
                var waarde = parseInt(this.getAttribute('data-waarde'));
                sterren.forEach(function(s, index) {
                    if (index < waarde) {
                        s.style.color = '#f39c12';
                    } else {
                        s.style.color = '#ccc';
                    }
                });
            });
        });

        // Validatie formulier
        var recensieForm = document.getElementById('recensie-form');
        if (recensieForm) {
            recensieForm.addEventListener('submit', function(e) {
                var beoordeling = document.getElementById('beoordeling-waarde').value;
                if (beoordeling == 0) {
                    e.preventDefault();
                    alert('Kies een beoordeling door op de sterren te klikken!');
                }
            });
        }
    </script>
</body>
</html>
