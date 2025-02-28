<?php include('includes/header.php'); ?>

<div class="container">
    <h2>État des Box</h2>

    <!-- Messages Flash -->
    <?php if ($this->session->flashdata('success')) : ?>
        <p class="success"> <?= htmlspecialchars($this->session->flashdata('success')) ?> </p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')) : ?>
        <p class="error"> <?= htmlspecialchars($this->session->flashdata('error')) ?> </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Taille</th>
                <th>Disponibilité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($boxes)) : ?>
                <?php foreach ($boxes as $box) : ?>
                    <tr>
                        <td>Box <?= htmlspecialchars($box->num); ?></td>
                        <td><?= htmlspecialchars($box->size); ?> m²</td>
                        <td><?= ($box->available) ? "Disponible" : "Occupé"; ?></td>
                        <td>
                            <a href="<?= site_url('Admin_Controller/modifier_box/' . htmlspecialchars($box->id_box)); ?>" class="btn">Modifier</a>
                        </td>
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
