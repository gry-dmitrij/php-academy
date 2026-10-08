<?php
/**
 * @var array $bets
 * @var int $user_id
 */
?>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach($bets as $bet): ?>
            <?php 
                $diff_time = get_dt_range(strip_tags($bet['date_finish']));
                $is_finished = $diff_time[0] === 0 && $diff_time[1] === 0;
                $is_won = $bet['winner_id'] === $user_id;
            ?>
            <tr class="rates__item <?= $is_finished && !$is_won ? 'rates__item--end' : '' ?><?= $is_won ? ' rates__item--win' : '' ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="/<?= htmlspecialchars($bet['img']) ?>" width="54" height="40" alt="<?= htmlspecialchars($bet['title']) ?>">
                    </div>
                    <div>
                        <h3 class="rates__title">
                            <a href="<?= htmlspecialchars(create_link('lot', ['id' => $bet['lot_id']])) ?>"><?= htmlspecialchars($bet['title']) ?></a>
                        </h3>
                        <?php if ($is_won): ?>
                            <p><?= htmlspecialchars($bet['contacts']) ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($bet['name_category']) ?>
                </td>
                <td class="rates__timer">
                    <?php
                        $classname = 'timer';
                        $classname .= $diff_time[0] < 1 && !$is_finished ? ' timer--finishing' : '';
                        $classname .= $is_won ? ' timer--win' : '';
                        if (!$is_finished) {
                            $classname .= $bet['is_max'] ? ' timer--leading' : ' timer--outbid';
                        }
                    ?>
                    <div class="<?= $classname ?>">
                        <?php if ($is_won): ?>
                            Ставка выиграла
                        <?php else: ?>
                            <?= sprintf("%d:%02d", ...$diff_time) ?>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= format_price($bet['price_bet']) ?>
                </td>
                <td class="rates__time">
                    <?=htmlspecialchars($bet['date_bet'])?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
