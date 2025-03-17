<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Tableau de Bord Administrateur</h2>

    <!-- Messages Flash -->
    <?php foreach (['success', 'error'] as $type): ?>
        <?php if ($message = $this->session->flashdata($type)) : ?>
            <p class="<?= $type ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    <?php endforeach; ?>

    <ul>
        <li><a href="<?= site_url('Admin_Controller/etat_box'); ?>">Gestion des Box</a></li>
        <li><a href="<?= site_url('Admin_Controller/historique_reservation'); ?>">Gestien des Réservations</a></li>
        <li><a href="<?= site_url('Admin_Controller/historique_connexion_client'); ?>">Gestien des Clients</a></li>
        <li><a href="<?= site_url('Code_Controller/gestion_code'); ?>">Gestien des Codes d'Accès</a></li>
    </ul>
</div>

<?php include('includes/footer.php'); ?>
