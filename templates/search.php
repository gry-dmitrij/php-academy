<?php
/**
 * @var string $search
 * @var array $lots
 * @var int $page_count
 * @var int $page
 * @var ?array $errors
 */
?>
<div class="container">
    <section class="lots">
        <?php if (!empty($errors['search'])): ?>
            <h2>Ошибка запроса: <?= htmlspecialchars($errors['search']) ?></h2>
        <?php elseif ($search === ''): ?>
            <h2>Введите поисковый запрос</h2>
        <?php else: ?>
            <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search) ?></span>»</h2>
            <?php if (!empty($lots)): ?>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot): ?>
                        <?= include_template('lot_item.php', [
                            "id" => $lot['id'],
                            "image" => $lot['img'],
                            "project" => $lot['name_category'],
                            "name" => $lot['title'],
                            "price" => $lot['start_price'],
                            "expired_date" => $lot['date_finish'],
                        ]) ?>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Ничего не найдено по вашему запросу</p>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <?php if ($page_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <?php if ($page > 1): ?>
                    <a href="<?= htmlspecialchars(create_search_link($search, $page - 1)) ?>">Назад</a>
                <?php else: ?>
                    <a>Назад</a>
                <?php endif; ?>
            </li>
            <?php foreach (create_pagination_range($page_count, $page) as $page_number): ?>
                <li class="pagination-item<?= $page_number === $page ? ' pagination-item-active' : '' ?>">
                    <?php if ($page_number === 0): ?>
                        <a>...</a>
                    <?php else: ?>
                        <a <?php if ($page_number !== $page): ?>href="<?= htmlspecialchars(create_search_link($search, $page_number)) ?>"<?php endif; ?>>
                            <?= $page_number ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <?php if ($page < $page_count): ?>
                    <a href="<?= htmlspecialchars(create_search_link($search, $page + 1)) ?>">Вперед</a>
                <?php else: ?>
                    <a>Вперед</a>
                <?php endif; ?>
            </li>
        </ul>
    <?php endif; ?>
</div>