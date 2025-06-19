<?php
/** @var string|null $message */
?>
    <h1>500 — Внутренняя ошибка сервера</h1>
    <p>Произошла непредвиденная ошибка. Пожалуйста, попробуйте позже.</p>
<?php if (!empty($message)): ?>
    <pre style="color: #a00;"><?= htmlspecialchars($message)?></pre>
<?php endif; ?>