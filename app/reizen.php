<?php
session_start();
require('db.php');

// Haal zoekterm op uit URL (komt van homepage of zoekbalk)
$zoek_bestemming = trim($_GET['bestemming'] ?? '');

// Haal reizen op, met optioneel filter op bestemming
if ($zoek_bestemming !== '') {
    $sql = "SELECT * FROM reizen WHERE actief = TRUE AND bestemming LIKE ? ORDER BY prijs ASC";
    $stmt = $pdo->prepare($sql);
    // LIKE met % zoekt op een deel van het woord
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
                <input type="text" id="destination" name="bestemming" placeholder="Waar wil je heen?"
                    value="<?php echo ($zoek_bestemming); ?>">
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
                    <input type="range" id="prijs-filter" class="price-range" min="200" max="1500" value="1500"
                        step="50">
                    <div class="price-labels">
                        <span>€ 200</span>
                        <span id="prijs-max-label">€ 1500</span>
                    </div>
                </div>

                <div class="filter-group">
                    <p class="filter-group-title">Beschikbaarheid</p>
                    <label class="filter-option">
                        <input type="radio" name="beschikbaarheid" value="alle" checked> Alle
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="beschikbaarheid" value="beschikbaar"> Beschikbaar
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="beschikbaarheid" value="bijna"> Bijna vol
                    </label>
                </div>
            </aside>

            <div class="results-section">
                <div class="results-header">
                    <h2><span id="aantal-gevonden"><?php echo $aantal_reizen; ?></span> reizen gevonden</h2>
                    <div class="sort-group">
                        <span>Sorteren op</span>
                        <select id="sorteer-select">
                            <option value="aanbevolen">Aanbevolen</option>
                            <option value="prijs-laag">Prijs (laag-hoog)</option>
                            <option value="prijs-hoog">Prijs (hoog-laag)</option>
                        </select>
                    </div>
                </div>

                <div class="reizen-grid" id="reizen-grid">
                    <?php
                    // Controleer of er reizen zijn
                    if ($aantal_reizen > 0) {
                        foreach ($alle_reizen as $reis) {
                            // Bereken aantal vrije plaatsen
                            $vrije_plaatsen = $reis['max_personen'] - $reis['geboekt_personen'];
                            if ($reis['max_personen'] > 0) {
                                $beschikbaarheid_procent = ($reis['geboekt_personen'] / $reis['max_personen']) * 100;
                            } else {
                                $beschikbaarheid_procent = 0;
                            }

                            // Bepaal beschikbaarheidsstatus
                            if ($vrije_plaatsen == 0) {
                                $beschikbaarheid_tag = '<span class="tag tag-volzet">Volzet</span>';
                            } elseif ($beschikbaarheid_procent >= 70) {
                                $beschikbaarheid_tag = '<span class="tag tag-almost">Bijna vol</span>';
                            } else {
                                $beschikbaarheid_tag = '<span class="tag tag-available">Beschikbaar</span>';
                            }

                            // Bereken aantal nachten
                            $start = new DateTime($reis['startdatum']);
                            $eind = new DateTime($reis['einddatum']);
                            $interval = $start->diff($eind);
                            $aantal_nachten = $interval->days;

                            // Status in een simpel woord voor het JavaScript-filter
                            if ($vrije_plaatsen == 0) {
                                $status = 'volzet';
                            } elseif ($beschikbaarheid_procent >= 70) {
                                $status = 'bijna';
                            } else {
                                $status = 'beschikbaar';
                            }
                            ?>
                            <?php $kleur = !empty($reis['kleur']) ? $reis['kleur'] : '#1b3a53'; ?>
                            <div class="trip-card" data-type="<?php echo ($reis['type_reis']); ?>"
                                data-prijs="<?php echo $reis['prijs']; ?>" data-status="<?php echo $status; ?>">
                                <div class="trip-image" style="background-color: <?php echo ($kleur); ?>;">
                                    <div class="trip-label"><?php echo strtoupper(($reis['bestemming'])); ?></div>
                                </div>
                                <div class="trip-tags">
                                    <span class="tag tag-default"><?php echo ($reis['type_reis']); ?></span>
                                    <?php echo $beschikbaarheid_tag; ?>
                                </div>
                                <h3><?php echo ($reis['bestemming']); ?></h3>
                                <p class="trip-location">📍 <?php echo ($reis['bestemming']); ?> ·
                                    <?php echo $aantal_nachten; ?>         <?php echo ($aantal_nachten == 1) ? 'nacht' : 'nachten'; ?>
                                </p>
                                <div class="trip-rating">★★★★★ 4.6</div>
                                <div class="trip-price">vanaf <strong>€
                                        <?php echo number_format($reis['prijs'], 2, ',', '.'); ?></strong> p.p.</div>
                                <a href="reis-detail.php?id=<?php echo $reis['id']; ?>" class="btn btn-primary trip-btn">Bekijk
                                    & boek</a>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="no-reizen-message">
                            <h3>Er zijn momenteel geen reizen beschikbaar</h3>
                            <p>Kom later terug!</p>
                        </div>
                        <?php
                    }
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
        // Alle kaarten en de filter-onderdelen ophalen
        var tripCards = document.querySelectorAll('.trip-card');
        var grid = document.getElementById('reizen-grid');
        var prijsFilter = document.getElementById('prijs-filter');
        var prijsLabel = document.getElementById('prijs-max-label');
        var sorteerSelect = document.getElementById('sorteer-select');
        var aantalLabel = document.getElementById('aantal-gevonden');

        // Deze functie past alle filters tegelijk toe
        function pasFiltersToe() {
            // Gekozen type ophalen
            var gekozenType = document.querySelector('input[name="type"]:checked').value;
            // Gekozen beschikbaarheid ophalen
            var gekozenStatus = document.querySelector('input[name="beschikbaarheid"]:checked').value;
            // Maximale prijs ophalen
            var maxPrijs = parseInt(prijsFilter.value);

            var aantalZichtbaar = 0;

            tripCards.forEach(function (card) {
                var kaartType = card.getAttribute('data-type');
                var kaartPrijs = parseFloat(card.getAttribute('data-prijs'));
                var kaartStatus = card.getAttribute('data-status');

                // Standaard tonen we de kaart
                var tonen = true;

                // Filter op type
                if (gekozenType !== 'Alle types' && kaartType !== gekozenType) {
                    tonen = false;
                }
                // Filter op prijs
                if (kaartPrijs > maxPrijs) {
                    tonen = false;
                }
                // Filter op beschikbaarheid
                if (gekozenStatus === 'beschikbaar' && kaartStatus !== 'beschikbaar') {
                    tonen = false;
                }
                if (gekozenStatus === 'bijna' && kaartStatus !== 'bijna') {
                    tonen = false;
                }

                if (tonen) {
                    card.style.display = 'flex';
                    aantalZichtbaar++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Het aantal gevonden reizen bovenaan bijwerken
            aantalLabel.textContent = aantalZichtbaar;
        }

        // Sorteren op prijs
        function sorteerKaarten() {
            var keuze = sorteerSelect.value;
            // Maak een gewone lijst van de kaarten
            var lijst = Array.prototype.slice.call(tripCards);

            lijst.sort(function (a, b) {
                var prijsA = parseFloat(a.getAttribute('data-prijs'));
                var prijsB = parseFloat(b.getAttribute('data-prijs'));

                if (keuze === 'prijs-laag') {
                    return prijsA - prijsB;
                } else if (keuze === 'prijs-hoog') {
                    return prijsB - prijsA;
                }
                return 0;
            });

            // Zet de kaarten in de nieuwe volgorde terug in de grid
            lijst.forEach(function (card) {
                grid.appendChild(card);
            });
        }

        // Luister naar alle filter-knoppen
        document.querySelectorAll('input[name="type"]').forEach(function (radio) {
            radio.addEventListener('change', pasFiltersToe);
        });
        document.querySelectorAll('input[name="beschikbaarheid"]').forEach(function (radio) {
            radio.addEventListener('change', pasFiltersToe);
        });

        // Prijs-schuif: label bijwerken en filteren
        if (prijsFilter) {
            prijsFilter.addEventListener('input', function () {
                prijsLabel.textContent = '€ ' + prijsFilter.value;
                pasFiltersToe();
            });
        }

        // Sorteren
        if (sorteerSelect) {
            sorteerSelect.addEventListener('change', sorteerKaarten);
        }
    </script>
</body>

</html>