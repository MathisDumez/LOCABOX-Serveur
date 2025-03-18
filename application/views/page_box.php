<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Détails du Box</h2>

    <?php include('includes/message.php'); ?>

    <?php if (!empty($box)) : ?>
        <p><strong>Numéro :</strong> <?= htmlspecialchars($box->num ?? 'Indisponible'); ?></p>
        <p><strong>Taille :</strong> <?= htmlspecialchars($box->size ?? 'Indisponible'); ?> m²</p>
        <p><strong>Bâtiment :</strong> <?= htmlspecialchars($box->warehouse_name ?? 'Indisponible'); ?></p>
        <p><strong>Disponibilité :</strong> <?= isset($box->available) ? ($box->available ? 'Oui' : 'Non') : 'Indisponible'; ?></p>

        <?php if (!empty($box->available)) : ?>
            <?php if (!$this->session->userdata('id_user_box')) : ?>
                <a href="<?= site_url('id/identification?redirect=' . urlencode(current_url())); ?>" class="btn">Se connecter pour réserver</a>
            <?php else : ?>
                <a href="<?= site_url('user/reserver?box_id=' . $box->id_box); ?>" class="btn">Réserver ce box</a>
            <?php endif; ?>
        <?php else : ?>
            <p class="error"><strong>Ce box est actuellement indisponible.</strong></p>
        <?php endif; ?>
    <?php else : ?>
        <p>Aucun détail disponible.</p>
    <?php endif; ?>

    <a href="<?= site_url('vitrine/index'); ?>" class="btn btn-cancel">Retour à la liste</a>
</div>

<?php include('includes/footer.php'); ?>