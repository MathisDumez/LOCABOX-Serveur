<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Box Disponibles</h2>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"> <?= htmlspecialchars($this->session->flashdata('error')) ?> </p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"> <?= htmlspecialchars($this->session->flashdata('success')) ?> </p>
    <?php endif; ?>

    <!-- Filtres de tri -->
    <form method="get" action="<?= site_url('Vitrine_Controller/index'); ?>">
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
                            <a href="<?= site_url('Vitrine_Controller/detail/' . htmlspecialchars($box->id_box)); ?>">
                                Box <?= htmlspecialchars($box->num); ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($box->size); ?> m²</td>
                        <td><?= htmlspecialchars($box->id_warehouse); ?></td>
                        <td><?= ($box->available) ? "Disponible" : "Occupé"; ?></td>
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
