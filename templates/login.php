<?php
/**
 * @var array $errors
 * @var array $values
 */
?>
<form class="form container <?php if (!empty($errors)): ?>form--invalid<?php endif; ?>" action="/login" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php
        $error = $errors['email'] ?? '';
    ?>
    <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars($values['email'] ?? '') ?>">
        <span class="form__error"><?= $error ?></span>
    </div>
    <?php
        $error = $errors['password'] ?? '';
    ?>
    <div class="form__item form__item--last <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $error ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>