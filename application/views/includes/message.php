<?php foreach (['success', 'error'] as $type): ?>
    <?php if ($message = $this->session->flashdata($type)) : ?>
        <p class="<?= $type ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
<?php endforeach; ?>