<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Réservation Confirmée</h2>

    <!-- Message de confirmation -->
    <p class="success">Votre réservation a été confirmée avec succès !</p>

    <p><a href="<?= site_url('User_Controller/dashboard'); ?>" class="btn">Voir mes réservations</a></p>
    <p><a href="<?= site_url('Vitrine_Controller/index'); ?>" class="btn-cancel">Retour à l'accueil</a></p>
</div>

<?php include('includes/footer.php'); ?>
