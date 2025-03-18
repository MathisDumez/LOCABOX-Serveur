<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Changer de Mot de Passe</h2>

    <?php include('includes/message.php'); ?>

    <form action="<?= site_url('user/update_password'); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        
        <label>Ancien Mot de Passe :</label>
        <input type="password" name="old_password" required>

        <label>Nouveau Mot de Passe :</label>
        <input type="password" name="new_password" required>

        <label>Confirmer le Nouveau Mot de Passe :</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn">Changer le mot de passe</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
