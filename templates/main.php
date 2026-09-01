<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $category): ?>
        <li class="promo__item promo__item--boards">
            <a class="promo__link" href="pages/all-lots.html"><?=$category?></a>
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
                "image" => $good['image'],
                "project" => $good['project'],
                "name" => $good['name'],
                "price" => $good['price'],
                "expired_date" => $good['expired_date'],
        ]));
        ?>
    </ul>
</section>