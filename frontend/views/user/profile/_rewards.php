<div class="account__item">
    <h5 class="account__subtitle">
        <?= Yii::t('app', 'Достижения') ?>
    </h5>
    
    <div class="account__achievements">
        <div class="achievements-leagues">
            <ul class="achievements-leagues__list">
                <?php foreach (array_slice($rewards['leagues'], 0, 4) as $league) { ?>
                    <?php if (!$league->league) continue ?>
                    
                    <li>
                        <button class="achievements-leagues__item"
                                data-sr-trigger="ajax-button"
                                data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                    'user/reward/view',
                                    'id' => $league->league_id,
                                    'type' => 'league',
                                ]) ?>"
                                data-sr-wrapper="#main-modal-wrapper"
                                data-sr-callback="$('#main-modal').addClass('_active');"
                        >
                            <?php if ($league->total_count > 1) { ?>
                                <span><?= $league->total_count ?></span>
                            <?php } ?>
                            
                            <img src="<?= $league->league->icon ?>" alt="">
                        </button>
                    </li>
                <?php } ?>
            </ul>
            
            <?php if ($other_leagues = array_slice($rewards['leagues'], 4, count($rewards['leagues']))) { ?>
                <div class="achievements-leagues__dropdown  achievements-dropdown-content">
                    <ul class="achievements-leagues__list">
                        <?php foreach ($other_leagues as $league) { ?>
                            <?php if (!$league->league) continue ?>
                            <li>
                                <button class="achievements-leagues__item"
                                        data-sr-trigger="ajax-button"
                                        data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                            'user/reward/view',
                                            'id' => $league->league_id,
                                            'type' => 'league',
                                        ]) ?>"
                                        data-sr-wrapper="#main-modal-wrapper"
                                        data-sr-callback="$('#main-modal').addClass('_active');"
                                >
                                    <?php if ($league->total_count > 1) { ?>
                                        <span><?= $league->total_count ?></span>
                                    <?php } ?>
                                    
                                    <img src="<?= $league->league->icon ?>" alt="">
                                </button>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                
                <button class="achievements-leagues__btn achievements-dropdown-btn _icon-arrow">
                    <span class="achievements-dropdown-btn__show">
                        <?= Yii::t('app', 'Показать все лиги') ?>
                    </span>
                    <span class="achievements-dropdown-btn__hide">
                        <?= Yii::t('app', 'Скрыть') ?>
                    </span>
                </button>
            <?php } ?>
        </div>
        
        <div class="achievements__progress">
            <ul class="achievements__progress-list">
                <?php foreach (array_slice($rewards['achievements'], 0, 4) as $achievement) { ?>
                    <li class="achievements__progress-item">
                        <button class="achievements__progress-icon"
                                data-sr-trigger="ajax-button"
                                data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                    'user/reward/view',
                                    'id' => $achievement->achievement_id,
                                    'type' => 'achievement',
                                ]) ?>"
                                data-sr-wrapper="#main-modal-wrapper"
                                data-sr-callback="$('#main-modal').addClass('_active');"
                        >
                            <?php if ($achievement->total_count > 1) { ?>
                                <span><?= $achievement->total_count ?></span>
                            <?php } ?>
                            
                            <img src="<?= $achievement->achievement->icon ?>" alt="">
                        </button>
                    </li>
                <?php } ?>
            </ul>
            
            <?php if ($other_achievements = array_slice($rewards['achievements'], 4, count($rewards['achievements']))) { ?>
                <div class="achievements__progress-dropdown achievements-dropdown-content">
                    <ul class="achievements__progress-list">
                        <?php foreach ($other_achievements as $achievement) { ?>
                            <li class="achievements__progress-item">
                                <button class="achievements__progress-icon"
                                        data-sr-trigger="ajax-button"
                                        data-sr-url="<?= Yii::$app->urlManager->createUrl([
                                            'user/reward/view',
                                            'id' => $achievement->achievement_id,
                                            'type' => 'achievement',
                                        ]) ?>"
                                        data-sr-wrapper="#main-modal-wrapper"
                                        data-sr-callback="$('#main-modal').addClass('_active');"
                                >
                                    <?php if ($achievement->total_count > 1) { ?>
                                        <span><?= $achievement->total_count ?></span>
                                    <?php } ?>
                                    
                                    <img src="<?= $achievement->achievement->icon ?>" alt="">
                                </button>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                
                <button class="achievements__progress-btn achievements-dropdown-btn _icon-arrow">
                    <span class="achievements-dropdown-btn__show">
                        <?= Yii::t('app', 'Показать все достижения') ?>
                    </span>
                    <span class="achievements-dropdown-btn__hide">
                        <?= Yii::t('app', 'Скрыть') ?>
                    </span>
                </button>
            <?php } ?>
        </div>
    </div>
</div>
