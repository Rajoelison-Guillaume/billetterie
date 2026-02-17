<footer class="footer text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Logo et description -->
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <h5 class="fw-bold text-info">Billetterie MG</h5>
                <p>Votre plateforme d’événements à Madagascar. Réservez vos billets en toute simplicité.</p>
            </div>

            <!-- Navigation -->
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <h6 class="fw-bold">Navigation</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="footer-link">Accueil</a></li>
                    <li><a href="{{ route('events.index') }}" class="footer-link">Événements</a></li>
                    <li><a href="{{ route('organizers.index') }}" class="footer-link">Organisateurs</a></li>
                    <li><a href="{{ route('orders.cart') }}" class="footer-link">Mon panier</a></li>
                </ul>
            </div>

            <!-- Réseaux sociaux -->
            <div class="col-lg-4 col-md-12 col-12 mb-4">
                <h6 class="fw-bold">Suivez-nous</h6>
                <div class="d-flex gap-4">
                    <a href="https://www.facebook.com" class="footer-social" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com" class="footer-social" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com" class="footer-social" target="_blank">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://twitter.com" class="footer-social" target="_blank">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-light">

        <div class="text-center mt-3">
            <small>&copy; {{ date('Y') }} Billetterie MG. Tous droits réservés.</small>
        </div>
    </div>
</footer>
