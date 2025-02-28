<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Tableau de Bord Administrateur</h2>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"> <?= htmlspecialchars($this->session->flashdata('success')) ?> </p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"> <?= htmlspecialchars($this->session->flashdata('error')) ?> </p>
    <?php endif; ?>

    <ul>
        <li><a href="<?= site_url('Admin_Controller/etat_box'); ?>">Gérer les Box</a></li>
        <li><a href="<?= site_url('Admin_Controller/historique_reservation'); ?>">Historique des Réservations</a></li>
        <li><a href="<?= site_url('Admin_Controller/historique_connexion_client'); ?>">Historique des Connexions</a></li>
        <li><a href="<?= site_url('Code_Controller/gestion_code'); ?>">Gérer les Codes d'Accès</a></li>
    </ul>
</div>

<?php include('includes/footer.php'); ?>
