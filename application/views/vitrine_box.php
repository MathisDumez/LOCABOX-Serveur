<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Box Disponibles</h2>

    <!-- Filtres de tri -->
    <form method="get" action="<?php echo site_url('Vitrine_Controller/index'); ?>">
        <label for="sort">Trier par :</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="">Sélectionner</option>
            <option value="size">Taille</option>
            <option value="available">Disponibilité</option>
            <option value="id_warehouse">Bâtiment</option>
        </select>
    </form>

    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Taille (m²)</th>
                <th>Bâtiment</th>
                <th>Disponibilité</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($boxes)) : ?>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo site_url('Vitrine_Controller/detail/' . $box->id_box); ?>">
                                Box <?php echo $box->num; ?>
                            </a>
                        </td>
                        <td><?php echo $box->size; ?> m²</td>
                        <td><?php echo $box->id_warehouse; ?></td>
                        <td><?php echo ($box->available) ? "Disponible" : "Occupé"; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Aucun box disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
