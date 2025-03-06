<footer>
    <div class="footer-content">
        <p>&copy; 2025 LOCABOX. Tous droits réservés.</p>
        <ul class="footer-links">
            <li><a href="<?php echo site_url('Vitrine_Controller/index'); ?>">Accueil</a></li>

            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <li><a href="<?php echo site_url('identification'); ?>">Connexion</a></li>
            <?php else : ?>
                <li><a href="<?php echo site_url('user/dashboard'); ?>">Tableau de bord</a></li>

                <?php if ($this->session->userdata('admin')) : ?>
                    <li><a href="<?php echo site_url('Admin_Controller/dashboard'); ?>">Tableau de bord</a></li>
                <?php endif; ?>

                <li><a href="<?php echo site_url('Identification_Controller/logout'); ?>">Déconnexion</a></li>
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
