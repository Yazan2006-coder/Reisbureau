<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Reisbureau</title>
</head>

<body>
    <?php require('includes/header.php'); ?>
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <p class="hero-label">CITYTRIPS - ZOMER 2026</p>
                <h1>Jouw wereld,<br>jouw koers.</h1>
                <p class="hero-description">Premium stedentrips door Europa, samengesteld door reisspecialisten. Vluchten, hotels en verzekeringen — alles in één booking.</p>
            </div>
            
            <form class="search-form" action="reizen.php" method="GET">
                <div class="form-group">
                    <label for="destination">Bestemming</label>
                    <input type="text" id="destination" name="bestemming" placeholder="Waar wil je heen?">
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
                        <option value="1">1 persoon</option>
                        <option value="2" selected>2 personen</option>
                        <option value="3">3 personen</option>
                        <option value="4">4 personen</option>
                        <option value="5">5 personen</option>
                    </select>
                </div>
                <button type="submit" class="search-btn">Zoeken</button>
            </form>
            
            <div class="benefits">
                <div class="benefit-item">✓ 25 jaar ervaring</div>
                <div class="benefit-item">✓ ANVR & SGR aangesloten</div>
                <div class="benefit-item">✓ 24/7 reisassistentie</div>
                <div class="benefit-item">✓ Beste prijsgarantie</div>
            </div>
        </div>
    </section>

    <section class="recommended">
        <div class="recommended-container">
            <div class="recommended-header">
                <div>
                    <p class="recommended-label">AANBEVOLEN</p>
                    <h2>Onze meest geboekte citytrips</h2>
                </div>
                <a href="reizen.php" class="btn-view-all">Bekijk alles →</a>
            </div>
            
            <div class="trips-grid">
                <div class="trip-card">
                    <div class="trip-image" style="background-color: #2E86DE;">
                        <div class="trip-label">PARIJS - EIFFELTOREN</div>
                    </div>
                    <div class="trip-tags">
                        <span class="tag tag-default">Citytrip</span>
                        <span class="tag tag-recommended">Aanbevolen</span>
                        <span class="tag tag-available">Beschikbaar</span>
                    </div>
                    <h3>Parijs — Stad van het licht</h3>
                    <p class="trip-location">📍 Parijs, Frankrijk · 4 nachten</p>
                    <div class="trip-rating">★★★★★ 4.7 (142)</div>
                    <div class="trip-price">vanaf <strong>€ 689</strong> p.p.</div>
                    <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk & boek</a>
                </div>
                
                <div class="trip-card">
                    <div class="trip-image" style="background-color: #E67E22;">
                        <div class="trip-label">ROME - COLOSSEUM</div>
                    </div>
                    <div class="trip-tags">
                        <span class="tag tag-default">Citytrip</span>
                        <span class="tag tag-popular">Populair</span>
                        <span class="tag tag-available">Beschikbaar</span>
                    </div>
                    <h3>Rome — Eeuwige stad</h3>
                    <p class="trip-location">📍 Rome, Italië · 5 nachten</p>
                    <div class="trip-rating">★★★★★ 4.8 (211)</div>
                    <div class="trip-price">vanaf <strong>€ 849</strong> p.p.</div>
                    <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk & boek</a>
                </div>
                
                <div class="trip-card">
                    <div class="trip-image" style="background-color: #E74C3C;">
                        <div class="trip-label">BARCELONA - SAGRADA FAMILIA</div>
                    </div>
                    <div class="trip-tags">
                        <span class="tag tag-default">Citytrip</span>
                        <span class="tag tag-recommended">Aanbieding</span>
                        <span class="tag tag-available">Blijvol</span>
                    </div>
                    <h3>Barcelona — Gaudí & strand</h3>
                    <p class="trip-location">📍 Barcelona, Spanje · 4 nachten</p>
                    <div class="trip-rating">★★★★★ 4.6 (178)</div>
                    <div class="trip-price">vanaf <strong>€ 599</strong> p.p.</div>
                    <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk & boek</a>
                </div>

                <div class="trip-card category-card">
                    <div class="trip-image" style="background-color: #1b3a53;"></div>
                    <div class="category-label">Citytrip</div>
                    <p class="category-count">12 bestemmingen</p>
                </div>

                <div class="trip-card category-card">
                    <div class="trip-image" style="background-color: #6C5B7B;"></div>
                    <div class="category-label">Cultuur & geschiedenis</div>
                    <p class="category-count">8 bestemmingen</p>
                </div>

                <div class="trip-card category-card">
                    <div class="trip-image" style="background-color: #27AE60;"></div>
                    <div class="category-label">Zon & strand</div>
                    <p class="category-count">6 bestemmingen</p>
                </div>
            </div>
        </div>
    </section>
    <section class="offers">
        <div class="offers-container">
            <div class="offers-header">
                <div>
                    <p class="offers-label">TIJDELIJK</p>
                    <h2>Aanbiedingen deze week</h2>
                </div>
            </div>
            
            <div class="offers-grid">
                <div class="offer-card">
                    <div class="offer-image" style="background-color: #E74C3C;">
                        <div class="offer-label">BARCELONA - SAGRADA FAMILIA</div>
                    </div>
                    <div class="offer-tags">
                        <span class="tag tag-default">Citytrip</span>
                        <span class="tag tag-popular">Aanbieeding</span>
                        <span class="tag tag-available">Blijvol</span>
                    </div>
                    <h3>Barcelona — Gaudí & strand</h3>
                    <p class="offer-location">📍 Barcelona, Spanje · 4 nachten</p>
                    <div class="offer-rating">★★★★★ 4.6 (178)</div>
                    <div class="offer-price">vanaf <strong>€ 599</strong> p.p.</div>
                    <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk & boek</a>
                </div>
                
                <div class="offer-card">
                    <div class="offer-image" style="background-color: #8E44AD;">
                        <div class="offer-label">PRAAG - KAREL BRUG</div>
                    </div>
                    <div class="offer-tags">
                        <span class="tag tag-default">Citytrip</span>
                        <span class="tag tag-popular">Aanbieeding</span>
                        <span class="tag tag-available">Beschikbaar</span>
                    </div>
                    <h3>Praag — Gouden stad</h3>
                    <p class="offer-location">📍 Praag, Tsjechië · 3 nachten</p>
                    <div class="offer-rating">★★★★☆ 4.5 (83)</div>
                    <div class="offer-price">vanaf <strong>€ 549</strong> p.p.</div>
                    <a href="reizen.php" class="btn btn-primary trip-btn">Bekijk & boek</a>
                </div>
            </div>
        </div>
    </section>

    <section class="why-horizont">
        <div class="why-container">
            <div class="why-content">
                <p class="why-label">WAAROM HORIZONT</p>
                <h2>25 jaar ervaring in premium reizen</h2>
                <p class="why-description">Onze reisspecialisten kennen elke bestemming persoonlijk. We selecteren hotels, vervoer en verzekeringen die bij jou passen — niet bij een algoritme.</p>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">12.500+</div>
                        <div class="stat-label">Tevreden reizigers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4.7 / 5</div>
                        <div class="stat-label">Gemiddelde beoordeling</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">60+</div>
                        <div class="stat-label">Bestemmingen wereldwijd</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24 / 7</div>
                        <div class="stat-label">Reisassistentie</div>
                    </div>
                </div>
            </div>
            
            <div class="office-image">
                <div class="office-image-placeholder">
                    <img src="Images/TFWCM.jpg" alt="Office">
                </div>
            </div>
        </div>
    </section>

    <?php require('includes/footer.php'); ?>
</body>
</html>