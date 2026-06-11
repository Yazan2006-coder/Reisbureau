<?php 
session_start();
require('db.php');

// Haal alle actieve reizen uit de database
$sql = "SELECT * FROM reizen WHERE actief = TRUE ORDER BY prijs ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
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
        <div class="search-form">
            <div class="form-group">
                <label for="destination">Bestemming</label>
                <input type="text" id="destination" placeholder="Waar wil je heen?">
            </div>
            <div class="form-group">
                <label for="departure">Vertrek vanaf</label>
                <input type="date" id="departure">
            </div>
            <div class="form-group">
                <label for="flexible">Flexibel</label>
                <select id="flexible">
                    <option>Exacte datum</option>
                    <option>± 1 dag</option>
                    <option>± 3 dagen</option>
                    <option>± 1 week</option>
                </select>
            </div>
            <div class="form-group">
                <label for="persons">Personen</label>
                <select id="persons">
                    <option>1</option>
                    <option selected>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6+</option>
                </select>
            </div>
            <button class="search-btn">Zoeken</button>
        </div>
    </section>

    <section class="reizen-main">
        <div class="reizen-container">
            <aside class="filters-sidebar">
                <h3>Filters</h3>

                <div class="filter-group">
                    <p class="filter-group-title">Type reis</p>
                    <label class="filter-option">
                        <input type="radio" name="type" checked> Alle types
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="type"> Citytrip
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="type"> Strand
                    </label>
                    <label class="filter-option">
                        <input type="radio" name="type"> Avontuur
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

                <div class="reizen-grid">
                    <?php 
                    // Controleer of er reizen zijn
                    if ($aantal_reizen > 0):
                        foreach ($alle_reizen as $reis):
                            // Bereken aantal vrije plaatsen
                            $vrije_plaatsen = $reis['max_personen'] - $reis['geboekt_personen'];
                            $beschikbaarheid_procent = ($reis['geboekt_personen'] / $reis['max_personen']) * 100;
                            
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
                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="<?php echo htmlspecialchars($reis['afbeelding_url']); ?>" alt="<?php echo htmlspecialchars($reis['bestemming']); ?>">
                            <div class="trip-label"><?php echo strtoupper(htmlspecialchars($reis['bestemming'])); ?></div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default"><?php echo htmlspecialchars($reis['type_reis']); ?></span>
                            <?php echo $beschikbaarheid_tag; ?>
                        </div>
                        <h3><?php echo htmlspecialchars($reis['bestemming']); ?> – <?php echo htmlspecialchars($reis['beschrijving']); ?></h3>
                        <p class="trip-location">📍 <?php echo htmlspecialchars($reis['bestemming']); ?> · <?php echo $aantal_nachten; ?> <?php echo ($aantal_nachten == 1) ? 'nacht' : 'nachten'; ?></p>
                        <div class="trip-rating">★★★★★ 4.5 (<?php echo $aantal_reizen; ?>)</div>
                        <div class="trip-price">vanaf <strong>€ <?php echo number_format($reis['prijs'], 2, ',', '.'); ?></strong> p.p.</div>
                        <p class="available-places">
                            <?php echo $vrije_plaatsen; ?> plaatsen beschikbaar
                        </p>
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
</body>
</html>
