<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Tableau de Bord Administrateur</h2>

    <?php include('includes/message.php'); ?>

    <ul>
        <li><a href="<?= site_url('admin/etat_box'); ?>">Gestion des Box</a></li>
        <li><a href="<?= site_url('admin/gestion_reservation'); ?>">Gestien des Réservations</a></li>
        <li><a href="<?= site_url('admin/gestion_client'); ?>">Gestien des Clients</a></li>
        <li><a href="<?= site_url('admin/gestion_code'); ?>">Gestien des Codes d'Accès</a></li>
    </ul>
</div>

<?php include('includes/footer.php'); ?>
