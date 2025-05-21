<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Modifier le Bâtiment</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_batiment'); ?>" class="btn">Retour</a>

    <form method="post" action="<?= site_url('admin/modifier_batiment_submit/' . $warehouse->id_warehouse); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label for="name">Nom :</label>
        <input type="text" name="name" id="name" value="<?= set_value('name', $warehouse->name); ?>" required>

        <label for="address">Adresse :</label>
        <input type="text" name="address" id="address" value="<?= set_value('address', $warehouse->address); ?>" required>

        <button type="submit" class="btn">Enregistrer les modifications</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
