<?php
/**
 * @var array $lot
 * @var array $bets
 * @var array $values
 * @var ?array $errors
 * @var ?bool $can_bet
 */

// текущая цена — максимальная ставка, а если ставок нет, то стартовая цена лота
$current_price = $bets ? max(array_column($bets, 'price_bet')) : $lot['start_price'];
$min_bet = get_min_bet($current_price, $lot['step']);
$errors ??= [];
$can_bet ??= false;
?>
<section class="lot-item container">
    <h2><?= htmlspecialchars($lot['title'])?></h2>
    <div class="lot-item__content">
    <div class="lot-item__left">
        <div class="lot-item__image">
        <img src="/<?=htmlspecialchars($lot['img'])?>" width="730" height="548" alt="<?= htmlspecialchars($lot['title']) ?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['name_category'])?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['lot_description'])?></p>
    </div>
    <div class="lot-item__right">
        <div class="lot-item__state">
            <?php
                $diff_time = get_dt_range($lot['date_finish']);
                $is_finishing = $diff_time[0] < 1;
            ?>
            <div class="lot-item__timer timer <?php if ($is_finishing): ?>timer--finishing<?php endif;?>">
                <?= sprintf("%d:%02d", ...$diff_time) ?>
            </div>
            <div class="lot-item__cost-state">
                <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=format_price($current_price) ?></span>
                </div>
                <?php if ($can_bet): ?>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= format_price($min_bet) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($can_bet): ?>
                <form class="lot-item__form" action="<?= create_link('lot', ['id' => $lot['id']]) ?>" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item<?= isset($errors['cost']) ? ' form__item--invalid' : '' ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?= $min_bet?>" value="<?= htmlspecialchars($values['cost'] ?? '') ?>">
                        <?php if (isset($errors['cost'])): ?>
                            <span class="form__error"><?= htmlspecialchars($errors['cost']) ?></span>
                        <?php endif; ?>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            <?php endif; ?>
            <?php if (!$can_bet && isset($errors['cost'])): ?>
                <span class="form__error form__error--show"><?= htmlspecialchars($errors['cost']) ?></span>
            <?php endif; ?>
        </div>
        <div class="history">
        <h3>История ставок (<span><?=count($bets)?></span>)</h3>
        <table class="history__list">
            <?php foreach ($bets as $bet):?>
            <tr class="history__item">
            <td class="history__name"><?=htmlspecialchars($bet['user_name'])?></td>
            <td class="history__price"><?=format_price($bet['price_bet'])?></td>
            <td class="history__time"><?=format_relative_date($bet['date_bet'])?></td>
            </tr>
            <?php endforeach;?>
        </table>
        </div>
    </div>
    </div>
</section>
