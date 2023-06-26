<nav class="header-mobile-menu__nav">
    <ul class="header-mobile-menu__list">
        <?php foreach ($menu as $m) { ?>
            <li>
                <a class="link-hover" href="<?= $m['url'] ?>">
                    <?= $m['name'] ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>