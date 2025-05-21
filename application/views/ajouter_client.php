<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Ajouter un Client</h2>

    <?php include('includes/message.php'); ?>

    <a href="<?= site_url('admin/gestion_client'); ?>" class="btn">Retour</a>

    <form action="<?= site_url('admin/insert_client'); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <label>Administrateur :</label>
        <select name="admin">
            <option value="0" selected>Non</option>
            <option value="1">Oui</option>
        </select>

        <br><br>
        <button type="submit" class="btn">Ajouter</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
