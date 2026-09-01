<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?= htmlspecialchars($image) ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?= htmlspecialchars($project) ?></span>
        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= htmlspecialchars($name) ?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?= format_price(strip_tags($price)) ?></span>
            </div>
            <?php
                $diff_time = get_dt_range(strip_tags($expired_date));
                $warn = intval($diff_time[0]) > 0
            ?>
            <div class="lot__timer timer <?php if ($diff_time[0] < 1): ?>timer--finishing<?php endif; ?>">
                <?= sprintf("%d:%02d", ...$diff_time) ?>
            </div>
        </div>
    </div>
</li>