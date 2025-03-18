<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Confirmation de la Réservation</h2>

    <?php include('includes/message.php'); ?>

    <?php if (!empty($reservation)) : ?>
        <p><strong>Numéro du Box :</strong> <?= htmlspecialchars($reservation['box_num']); ?></p>
        <p><strong>Taille :</strong> <?= htmlspecialchars($reservation['box_size']); ?> m²</p>
        <p><strong>Bâtiment :</strong> <?= htmlspecialchars($reservation['warehouse_name']); ?></p>

        <form action="<?= site_url('user/valider_reservation'); ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            <input type="hidden" name="box_id" value="<?= htmlspecialchars($reservation['box_id']); ?>">

            <label for="start_date">Date de début :</label>
            <input type="date" id="start_date" name="start_date" required min="<?= date('Y-m-d'); ?>">

            <label for="end_date">Date de fin :</label>
            <input type="date" id="end_date" name="end_date" required min="<?= date('Y-m-d', strtotime('+1 day')); ?>">

            <button type="submit" class="btn">Confirmer la réservation</button>
            <a href="javascript:history.back();" class="btn btn-cancel">Annuler</a>
        </form>
    <?php else : ?>
        <div class="alert alert-warning">Aucune réservation en attente.</div>
        <a href="<?= site_url('vitrine/index'); ?>" class="btn">Retour à la liste des box</a>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>