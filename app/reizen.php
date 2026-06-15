<?php
session_start();
require('db.php');

// Haal zoekterm op uit URL (komt van homepage of zoekbalk)
$zoek_bestemming = trim($_GET['bestemming'] ?? '');

// Haal reizen op, met optioneel filter op bestemming
if ($zoek_bestemming !== '') {
    $sql = "SELECT * FROM reizen WHERE actief = TRUE AND bestemming LIKE ? ORDER BY prijs ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['%' . $zoek_bestemming . '%']);
} else {
    $sql = "SELECT * FROM reizen WHERE actief = TRUE ORDER BY prijs ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$alle_reizen = $stmt->fetchAll();

// Tel het aantal reizen
$aantal_reizen = count($alle_reizen);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Reizen – Horizont Reizen</title>
</head>
<body>
    <?php require('includes/header.php'); ?>

    <section class="search-section">
        <form class="search-form" method="GET" action="reizen.php">
            <div class="form-group">
                <label for="destination">Bestemming</label>
                <input type="text" id="destination" name="bestemming" placeholder="Waar wil je heen?" value="<?php echo htmlspecialchars($zoek_bestemming); ?>">
            </div>
            <div class="form-group">
                <label for="departure">Vertrek vanaf</label>
                <input type="date" id="departure" name="vertrek">
            </div>
            <div class="form-group">
                <label for="flexible">Flexibel</label>
                <select id="flexible" name="flexibel">
                    <option>Exacte datum</option>
                    <option>± 1 dag</option>
                    <option>± 3 dagen</option>
                    <option>± 1 week</option>
                </select>
            </div>
            <div class="form-group">
                <label for="persons">Personen</label>
                <select id="persons" name="personen">
                    <option value="1">1</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6+</option>
                </select>
            </div>
            <button type="submit" class="search-btn">Zoeken</button>
        </form>
    </section>

    <section class="reizen-main">
        <div class="reizen-container">
            <aside class="filters-sidebar">
                <h3>Filters</h3>

                <div class="filter-group">
                    <p class="filter-group-title">Type reis</p>
                    <label class="filter-option">
                        <input type="radio" name="type" value="Alle types" checked> Alle types
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="type" value="Citytrip"> Citytrip
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="type" value="Strand"> Strand
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="type" value="Avontuur"> Avontuur
                    </label>
                </div>

                <div class="filter-group">
                    <p class="filter-group-title">Max. prijs p.p.</p>
                    <input type="range" class="price-range" min="200" max="1500" value="1500">
                    <div class="price-labels">
                        <span>€ 200</span>
                        <span>€ 1500</span>
                    </div>
                </div>

                <div class="filter-group">
                    <p class="filter-group-title">Beschikbaarheid</p>
                    <label class="filter-option">
                        <input type="checkbox" checked> Alle
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" checked> Beschikbaar
                    </label>
                    <label class="filter-option">
                        <input type="checkbox"> Bijna vol
                    </label>
                </div>
            </aside>

            <div class="results-section">
                <div class="results-header">
                    <h2><?php echo $aantal_reizen; ?> reizen gevonden</h2>
                    <div class="sort-group">
                        <span>Sorteren op</span>
                        <select>
                            <option>Aanbevolen</option>
                            <option>Prijs (laag-hoog)</option>
                            <option>Prijs (hoog-laag)</option>
                            <option>Beoordeling</option>
                        </select>
                    </div>
                </div>

                <div class="reizen-grid" id="reizen-grid">
                    <?php
                    // Controleer of er reizen zijn
                    if ($aantal_reizen > 0):
                        foreach ($alle_reizen as $reis):
                            // Bereken aantal vrije plaatsen
                            $vrije_plaatsen = $reis['max_personen'] - $reis['geboekt_personen'];
                            if ($reis['max_personen'] > 0) {
                                $beschikbaarheid_procent = ($reis['geboekt_personen'] / $reis['max_personen']) * 100;
                            } else {
                                $beschikbaarheid_procent = 0;
                            }

                            // Bepaal beschikbaarheidsstatus
                            if ($vrije_plaatsen == 0):
                                $beschikbaarheid_tag = '<span class="tag tag-volzet">Volzet</span>';
                            elseif ($beschikbaarheid_procent >= 70):
                                $beschikbaarheid_tag = '<span class="tag tag-almost">Bijna vol</span>';
                            else:
                                $beschikbaarheid_tag = '<span class="tag tag-available">Beschikbaar</span>';
                            endif;

                            // Bereken aantal nachten
                            $start = new DateTime($reis['startdatum']);
                            $eind = new DateTime($reis['einddatum']);
                            $interval = $start->diff($eind);
                            $aantal_nachten = $interval->days;
                    ?>
                    <?php $kleur = !empty($reis['kleur']) ? $reis['kleur'] : '#1b3a53'; ?>
                    <div class="trip-card" data-type="<?php echo htmlspecialchars($reis['type_reis']); ?>">
                        <div class="trip-image" style="background-color: <?php echo htmlspecialchars($kleur); ?>;">
                            <div class="trip-label"><?php echo strtoupper(htmlspecialchars($reis['bestemming'])); ?></div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default"><?php echo htmlspecialchars($reis['type_reis']); ?></span>
                            <?php echo $beschikbaarheid_tag; ?>
                        </div>
                        <h3><?php echo htmlspecialchars($reis['bestemming']); ?></h3>
                        <p class="trip-location">📍 <?php echo htmlspecialchars($reis['bestemming']); ?> · <?php echo $aantal_nachten; ?> <?php echo ($aantal_nachten == 1) ? 'nacht' : 'nachten'; ?></p>
                        <div class="trip-rating">★★★★★ 4.6</div>
                        <div class="trip-price">vanaf <strong>€ <?php echo number_format($reis['prijs'], 2, ',', '.'); ?></strong> p.p.</div>
                        <a href="reis-detail.php?id=<?php echo $reis['id']; ?>" class="btn btn-primary trip-btn">Bekijk & boek</a>
                    </div>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <div class="no-reizen-message">
                        <h3>Er zijn momenteel geen reizen beschikbaar</h3>
                        <p>Kom later terug!</p>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php 
    // Toon admin link als gebruiker admin is
    if (isset($_SESSION['gebruiker_rol']) && $_SESSION['gebruiker_rol'] === 'admin'): 
    ?>
    <section class="admin-link-section">
        <a href="admin/reizen.php">→ Admin Panel: Reizen beheren</a>
    </section>
    <?php endif; ?>
    
    <?php require('includes/footer.php'); ?>

    <script>
        // Filter op type reis met de radio buttons
        var typeRadios = document.querySelectorAll('input[name="type"]');
        var tripCards = document.querySelectorAll('.trip-card');

        typeRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                var gekozenType = this.value;

                tripCards.forEach(function(card) {
                    var kaartType = card.getAttribute('data-type');

                    if (gekozenType === 'Alle types' || kaartType === gekozenType) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Zoekfunctie: filter op bestemming naam
        var zoekBtn = document.querySelector('.search-btn');
        var destinationInput = document.getElementById('destination');

        if (zoekBtn && destinationInput) {
            zoekBtn.addEventListener('click', function() {
                var zoekterm = destinationInput.value.toLowerCase().trim();

                tripCards.forEach(function(card) {
                    var naam = card.querySelector('h3').textContent.toLowerCase();
                    if (zoekterm === '' || naam.includes(zoekterm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    </script>
</body>
</html>
