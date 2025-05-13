<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Ajouter un Bâtiment</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_batiment'); ?>" class="btn">Retour</a>

    <form method="POST" action="<?= site_url('admin/ajouter_batiment_submit'); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label for="name">Nom du bâtiment :</label>
        <input type="text" name="name" id="name" required>

        <label for="address">Adresse :</label>
        <input type="text" name="address" id="address" required>

        <button type="submit" class="btn">Ajouter</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
