<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Modifier un Client</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_client'); ?>" class="btn">Retour</a>

    <form action="<?= site_url('admin/update_client/' . $user->id_user_box); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">    

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user->email); ?>" required>

        <label>Administrateur :</label>
        <select name="admin">
            <option value="0" <?= $user->admin == 0 ? 'selected' : ''; ?>>Non</option>
            <option value="1" <?= $user->admin == 1 ? 'selected' : ''; ?>>Oui</option>
        </select>

        <br><br>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
