<?php
/**
 * @var array $categories
 * @var array $errors
 * @var array $values
 */
?>
<form class="form form--add-lot container <?php if (count($errors)): ?>form--invalid<?php endif;?>" action="/add" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
    <?php
        $error = $errors['lot-name'] ?? '';
    ?>
    <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="lot-name">Наименование <sup>*</sup></label>
        <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= htmlspecialchars($values['lot-name'] ?? '')  ?>">
        <span class="form__error">Введите наименование лота</span>
    </div>
    <?php
        $error = $errors['category'] ?? '';
    ?>
    <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label for="category">Категория <sup>*</sup></label>
        <select id="category" name="category">
            <option value="">Выберите категорию</option>
            <?php $selected_category = $values['category'] ?? null; ?>
            <?php foreach ($categories as $category ): ?>
            <option
                value="<?= htmlspecialchars($category["character_code"] ?? '')?>"
                <?php if ($category["character_code"] === $selected_category): ?>selected<?php endif ?>
            ><?= htmlspecialchars($category["name_category"])?></option>
            <?php endforeach; ?>
        </select>
        <span class="form__error">Выберите категорию</span>
    </div>
    </div>
    <?php
        $error = $errors['message'] ?? '';
    ?>
    <div class="form__item form__item--wide <?php if ($error): ?>form__item--invalid<?php endif;?>">
    <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"><?= htmlspecialchars($values['message'] ?? '') ?></textarea>
    <span class="form__error">Напишите описание лота</span>
    </div>
    <?php
        $error = $errors['lot-img'] ?? '';
    ?>
    <div class="form__item form__item--file <?php if ($error): ?>form__item--invalid<?php endif;?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
            <label for="lot-img">
            Добавить
            </label>
        </div>
        <span class="form__error"><?=  htmlspecialchars($error) ?></span>
    </div>
    <div class="form__container-three">
        <?php
            $error = $errors['lot-rate'] ?? '';
        ?>
        <div class="form__item form__item--small <?php if ($error): ?>form__item--invalid<?php endif;?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= htmlspecialchars($values['lot-rate'] ?? '') ?>">
            <span class="form__error"><?= htmlspecialchars($error)?></span>
        </div>
        <?php
            $error = $errors['lot-step'] ?? '';
        ?>
        <div class="form__item form__item--small <?php if ($error): ?>form__item--invalid<?php endif;?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= htmlspecialchars($values['lot-step'] ?? '') ?>">
            <span class="form__error"><?= htmlspecialchars($error) ?></span>
        </div>
        <?php
            $error = $errors['lot-date'] ?? '';
        ?>
        <div class="form__item <?php if ($error): ?>form__item--invalid<?php endif;?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=htmlspecialchars($values['lot-date'] ?? '')?>">
            <span class="form__error"><?= htmlspecialchars($error) ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
