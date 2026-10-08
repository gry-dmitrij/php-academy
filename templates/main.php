<?php
/**
 * @var array $categories
 * @var array $goods
 */
?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--<?=$category['character_code']?>">
            <a class="promo__link" href="/pages/all-lots.html"><?=$category['name_category']?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($goods as $good)
            print(include_template('lot_item.php', [
                "id" => $good['id'],
                "image" => $good['img'],
                "project" => $good['name_category'],
                "name" => $good['title'],
                "price" => $good['start_price'],
                "expired_date" => $good['date_finish'],
        ]));
        ?>
    </ul>
</section>
