<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Gestion des Clients</h2>

    <?php include('includes/message.php'); ?>

    <table>
        <thead>
            <tr>
                <th>Email</th>
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
