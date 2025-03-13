<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Historique des Connexions des Clients</h2>

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
                <th>Utilisateur</th>
                <th>Date et Heure</th>
                <th>Adresse IP</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($logins)) : ?>
                <?php foreach ($logins as $login) : ?>
                    <tr>
                        <td><?= htmlspecialchars($login->user_email); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($login->login_time)); ?></td>
                        <td><?= htmlspecialchars($login->ip_address); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucune connexion enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
