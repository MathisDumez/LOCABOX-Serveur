<footer>
    <div class="footer-content">
        <p>&copy; 2025 LOCABOX. Tous droits réservés.</p>
        <ul class="footer-links">
            <li><a href="<?php echo site_url('vitrine/index'); ?>">Accueil</a></li>

            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <li><a href="<?php echo site_url('id/identification'); ?>">Connexion</a></li>
            <?php else : ?>
                <?php if (!$this->session->userdata('admin')) : ?>
                    <li><a href="<?php echo site_url('user/dashboard'); ?>">Mes Réservations</a></li>
                <?php endif; ?>

                <?php if ($this->session->userdata('admin')) : ?>
                    <li><a href="<?php echo site_url('admin/dashboard'); ?>">Tableau de bord</a></li>
                <?php endif; ?>

                <li><a href="<?php echo site_url('id/logout'); ?>">Déconnexion</a></li>
            <?php endif; ?>
        </ul>

        <div class="footer-social-icons">
            <a href="#"><img src="<?php echo base_url('assets/icons/facebook.png'); ?>" alt="Facebook"></a>
            <a href="#"><img src="<?php echo base_url('assets/icons/twitter.svg'); ?>" alt="Twitter"></a>
            <a href="#"><img src="<?php echo base_url('assets/icons/instagram.png'); ?>" alt="Instagram"></a>
        </div>
    </div>
</footer>

</body>
</html>