<?php session_start(); ?>
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
                    <h2>6 reizen gevonden</h2>
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
                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="images/paris.jpg" alt="Parijs">
                            <div class="trip-label">PARIJS - EIFFELTOREN</div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default">Citytrip</span>
                            <span class="tag tag-recommended">Aanbevolen</span>
                            <span class="tag tag-available">Beschikbaar</span>
                        </div>
                        <h3>Parijs – Stad van het licht</h3>
                        <p class="trip-location">&#128205; Parijs, Frankrijk · 4 nachten</p>
                        <div class="trip-rating">★★★★★ 4.7 (142)</div>
                        <div class="trip-price">vanaf <strong>€ 689</strong> p.p.</div>
                    </div>

                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="images/rome.jpg" alt="Rome">
                            <div class="trip-label">ROME - COLOSSEUM</div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default">Citytrip</span>
                            <span class="tag tag-popular">Populair</span>
                            <span class="tag tag-available">Beschikbaar</span>
                        </div>
                        <h3>Rome – Eeuwige stad</h3>
                        <p class="trip-location">&#128205; Rome, Italië · 5 nachten</p>
                        <div class="trip-rating">★★★★★ 4.8 (211)</div>
                        <div class="trip-price">vanaf <strong>€ 849</strong> p.p.</div>
                    </div>

                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="images/barcelona.jpg" alt="Barcelona">
                            <div class="trip-label">BARCELONA - SAGRADA FAMÍLIA</div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default">Citytrip</span>
                            <span class="tag tag-recommended">Aanbieding</span>
                            <span class="tag tag-almost">Bijna vol</span>
                        </div>
                        <h3>Barcelona – Gaudí & strand</h3>
                        <p class="trip-location">&#128205; Barcelona, Spanje · 4 nachten</p>
                        <div class="trip-rating">★★★★★ 4.6 (178)</div>
                        <div class="trip-price">vanaf <strong>€ 599</strong> p.p.</div>
                    </div>

                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="images/lissabon.jpg" alt="Lissabon">
                            <div class="trip-label">LISSABON - ALFAMA</div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default">Citytrip</span>
                            <span class="tag tag-available">Beschikbaar</span>
                        </div>
                        <h3>Lissabon – Tegels & tram 28</h3>
                        <p class="trip-location">&#128205; Lissabon, Portugal · 4 nachten</p>
                        <div class="trip-rating">★★★★★ 4.7 (96)</div>
                        <div class="trip-price">vanaf <strong>€ 729</strong> p.p.</div>
                    </div>

                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="images/prague.jpg" alt="Praag">
                            <div class="trip-label">PRAAG - KARELSBRUG</div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default">Citytrip</span>
                            <span class="tag tag-recommended">Aanbieding</span>
                            <span class="tag tag-available">Beschikbaar</span>
                        </div>
                        <h3>Praag – Gouden stad</h3>
                        <p class="trip-location">&#128205; Praag, Tsjechië · 3 nachten</p>
                        <div class="trip-rating">★★★★☆ 4.5 (83)</div>
                        <div class="trip-price">vanaf <strong>€ 549</strong> p.p.</div>
                    </div>

                    <div class="trip-card">
                        <div class="trip-image">
                            <img src="images/wenen.jpg" alt="Wenen">
                            <div class="trip-label">WENEN - SCHÖNBRUNN</div>
                        </div>
                        <div class="trip-tags">
                            <span class="tag tag-default">Citytrip</span>
                            <span class="tag tag-available">Beschikbaar</span>
                        </div>
                        <h3>Wenen – Klassiek & koffie</h3>
                        <p class="trip-location">&#128205; Wenen, Oostenrijk · 4 nachten</p>
                        <div class="trip-rating">★★★★★ 4.6 (71)</div>
                        <div class="trip-price">vanaf <strong>€ 779</strong> p.p.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>
