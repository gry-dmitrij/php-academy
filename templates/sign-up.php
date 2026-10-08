<?php
/**
 * @var array $errors
 * @var array $values
 */
?>
<form class="form container <?php if (count($errors)):?>form--invalid<?php endif; ?>" action="/sign-up" method="post" autocomplete="off"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
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
    <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $error ?></span>
    </div>
    <?php
        $error = $errors['name'] ?? '';
    ?>
    <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars($values['name'] ?? '') ?>">
        <span class="form__error"><?= $error ?></span>
    </div>
    <?php
        $error = $errors['message'] ?? '';
    ?>
    <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=
            htmlspecialchars($values['message'] ?? '')
        ?></textarea>
        <span class="form__error"><?= $error ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
