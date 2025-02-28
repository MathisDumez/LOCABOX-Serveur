<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Inscription</h2>

    <!-- Messages Flash -->
    <?php foreach (['success', 'error'] as $type): ?>
        <?php if ($message = $this->session->flashdata($type)) : ?>
            <p class="<?= $type ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    <?php endforeach; ?>

    <form action="<?= site_url('Identification_Controller/process_inscription'); ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <label>Email :</label>
        <input type="email" name="email" value="<?= set_value('email'); ?>" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="<?= site_url('Identification_Controller/identification'); ?>">Se connecter</a></p>
</div>

<?php include('includes/footer.php'); ?>
