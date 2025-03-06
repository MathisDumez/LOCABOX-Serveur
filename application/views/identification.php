<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Connexion</h2>

    <!-- Messages Flash -->
    <?php foreach (['success', 'error'] as $type): ?>
        <?php if ($message = $this->session->flashdata($type)) : ?>
            <p class="<?= $type ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    <?php endforeach; ?>

    <form action="<?= site_url('Identification_Controller/login'); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        
        <label>Email :</label>
        <input type="email" name="email" value="<?= set_value('email'); ?>" required>
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        
        <button type="submit" class="btn">Se connecter</button>
    </form>

    <p>Pas encore inscrit ? <a href="<?= site_url('inscription'); ?>">Créer un compte</a></p>
</div>

<?php include('includes/footer.php'); ?>