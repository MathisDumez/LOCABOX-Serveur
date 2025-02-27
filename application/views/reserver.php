<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Confirmation de la Réservation</h2>

    <!-- Messages de succès ou d'erreur -->
    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($reservation)) : ?>
        <p><strong>Numéro du Box :</strong> <?= htmlspecialchars($reservation['box_num']); ?></p>
        <p><strong>Taille :</strong> <?= htmlspecialchars($reservation['box_size']); ?> m²</p>
        <p><strong>Bâtiment :</strong> <?= htmlspecialchars($reservation['id_warehouse']); ?></p>
        <p><strong>Période de réservation :</strong></p>
        <p><strong>Début :</strong> <?= date('d/m/Y', strtotime($reservation['start_date'])); ?></p>
        <p><strong>Fin :</strong> <?= date('d/m/Y', strtotime($reservation['end_date'])); ?></p>

        <!-- Ne pas afficher le formulaire si la réservation est confirmée -->
        <?php if (!$this->session->flashdata('success')) : ?>
            <form action="<?= site_url('User_Controller/valider_reservation'); ?>" method="post">
                <input type="hidden" name="box_id" value="<?= htmlspecialchars($reservation['box_id']); ?>">
                <input type="hidden" name="start_date" value="<?= htmlspecialchars($reservation['start_date']); ?>">
                <input type="hidden" name="end_date" value="<?= htmlspecialchars($reservation['end_date']); ?>">

                <button type="submit" class="btn">Confirmer la réservation</button>
                <a href="javascript:history.back();" class="btn-cancel">Annuler</a>
            </form>
        <?php endif; ?>

    <?php else : ?>
        <div class="alert alert-warning">Aucune réservation en attente.</div>
        <a href="<?= site_url('Vitrine_Controller/index'); ?>" class="btn">Retour à la liste des box</a>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>