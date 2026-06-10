    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="footer-logo-text">Horizont Reizen</div>
                        <div class="footer-logo-tagline">JOUW WERELD, JOUW KOERS</div>
                    </div>
                    <p class="footer-description">Horizont Reizen specialiseert in meer dan 25 jaar premium reizen naar de mooiste bestemmingen van Europa en daarbuiten.</p>
                </div>
                
                <div class="footer-section">
                    <h4>REIZEN</h4>
                    <ul class="footer-links">
                        <li><a href="reizen.php">Alle reizen</a></li>
                        <li><a href="reizen.php">Citytrips</a></li>
                        <li><a href="reizen.php">Aanbiedingen</a></li>
                        <li><a href="reizen.php">Last minute</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>SERVICE</h4>
                    <ul class="footer-links">
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="overons.php">Over ons</a></li>
                        <li><a href="guidelines.php">Algemene voorwaarden</a></li>
                        <li><a href="guidelines.php">Privacy policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>CONTACT</h4>
                    <div class="footer-contact">
                        <p>Hayendaalsweg 98,<br>Nijmegen</p>
                        <p>030 - 123 45 67</p>
                        <p><a href="mailto:info@horizont.nl">info@horizont.nl</a></p>
                        <p class="footer-certifications">ANVR · SGR · Calamiteitenfonds</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <button id="scrollToTopBtn" class="scroll-to-top" title="Scroll to top">↑</button>
    <script>
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        // Show button when user scrolls down
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
