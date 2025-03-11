<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Détails du Box</h2>

    <?php if (!empty($box)) : ?>
        <p><strong>Numéro :</strong> <?= htmlspecialchars($box->num ?? 'Indisponible'); ?></p>
        <p><strong>Taille :</strong> <?= htmlspecialchars($box->size ?? 'Indisponible'); ?> m²</p>
        <p><strong>Bâtiment :</strong> <?= htmlspecialchars($box->warehouse_name ?? 'Indisponible'); ?></p>
        <p><strong>Disponibilité :</strong> <?= isset($box->available) ? ($box->available ? 'Oui' : 'Non') : 'Indisponible'; ?></p>

        <!-- Messages Flash -->
        <?php if ($this->session->flashdata('error')) : ?>
            <p class="error"><?= htmlspecialchars($this->session->flashdata('error')) ?></p>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')) : ?>
            <p class="success"><?= htmlspecialchars($this->session->flashdata('success')) ?></p>
        <?php endif; ?>

        <!-- Bouton de réservation -->
        <?php if (!empty($box->available)) : ?>
            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <a href="<?= site_url('identification?redirect=' . urlencode(current_url())); ?>" class="btn">Se connecter pour réserver</a>
            <?php else : ?>
                <a href="<?= site_url('user/reserver?box_id=' . $box->id_box); ?>" class="btn">Réserver ce box</a>
            <?php endif; ?>
        <?php else : ?>
            <p class="error"><strong>Ce box est actuellement indisponible.</strong></p>
        <?php endif; ?>
    <?php else : ?>
        <p>Aucun détail disponible.</p>
    <?php endif; ?>

    <a href="<?= site_url('Vitrine_Controller/index'); ?>" class="btn btn-cancel">Retour à la liste</a>
</div>

<?php include('includes/footer.php'); ?>