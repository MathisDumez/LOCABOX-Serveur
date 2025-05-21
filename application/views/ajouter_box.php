<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Ajouter un Box</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_box'); ?>" class="btn">Retour</a>

    <form method="POST" action="<?= site_url('admin/ajouter_box_submit'); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label for="num">Numéro du Box :</label>
        <input type="number" name="num" id="num" required min="1">

        <label for="size">Taille (m²) :</label>
        <select name="size" id="size" required>
            <option value="7">7 m²</option>
            <option value="40">40 m²</option>
        </select>

        <label for="id_warehouse">Bâtiment :</label>
        <select name="id_warehouse" id="id_warehouse" required>
            <?php if (!empty($warehouses)) : ?>
                <?php foreach ($warehouses as $warehouse) : ?>
                    <option value="<?= $warehouse->id_warehouse; ?>">
                        <?= htmlspecialchars($warehouse->name); ?>
                    </option>
                <?php endforeach; ?>
            <?php else : ?>
                <option value="">Aucun bâtiment disponible</option>
            <?php endif; ?>
        </select>

        <label for="available">Disponibilité :</label>
        <select name="available" id="available">
            <option value="1">Disponible</option>
            <option value="0">Occupée</option>
        </select>

        <button type="submit" class="btn">Ajouter</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
