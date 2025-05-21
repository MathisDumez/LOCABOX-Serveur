<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Tableau de Bord Administrateur</h2>

    <?php include('includes/message.php'); ?>

    <ul>
        <li><a href="<?= site_url('admin/gestion_box'); ?>">Gestion des Boxs</a></li>
        <li><a href="<?= site_url('admin/gestion_batiment'); ?>">Gestion des Bâtiments</a></li>
        <li><a href="<?= site_url('admin/gestion_reservation'); ?>">Gestion des Réservations</a></li>
        <li><a href="<?= site_url('admin/gestion_client'); ?>">Gestion des Clients</a></li>
        <li><a href="<?= site_url('admin/gestion_code'); ?>">Gestion des Codes d'Accès</a></li>
    </ul>
</div>

<?php include('includes/footer.php'); ?>
