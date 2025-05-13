<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Modifier le Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_box') ?>" class="btn" style="margin-bottom: 10px;">Retour à la gestion des box</a><br>

    <form action="<?= site_url('admin/modifier_box_submit/' . $box->id_box); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label for="num">Numéro :</label>
        <input type="number" name="num" value="<?= set_value('num', $box->num); ?>" required><br>

        <label for="size">Taille (m²) :</label>
        <input type="number" name="size" value="<?= set_value('size', $box->size); ?>" required><br>

        <label for="available">Disponibilité :</label>
        <select name="available">
            <option value="1" <?= $box->available ? 'selected' : ''; ?>>Disponible</option>
            <option value="0" <?= !$box->available ? 'selected' : ''; ?>>Occupé</option>
        </select><br>

        <label for="id_warehouse">Bâtiment :</label>
        <select name="id_warehouse" required>
            <?php foreach ($warehouses as $warehouse): ?>
                <option value="<?= $warehouse->id_warehouse ?>" <?= $box->id_warehouse == $warehouse->id_warehouse ? 'selected' : '' ?>>
                    <?= $warehouse->name ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" value="Enregistrer" class="btn">

    </form>
</div>

<?php include('includes/footer.php'); ?>
