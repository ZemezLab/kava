<div class="travel-section travel-services">
    <ul class="list-content">
        <?php foreach (explode("\n", strip_tags($list)) as $item): ?>
            <?php if (isset($item) && $item !== '' && strlen($item) <= 200 && strlen($item) > 3): ?>
                <li>
                    <span class="icon"><?php \Elementor\Icons_Manager::render_icon($settings['icon']); ?></span>
                    <?php echo $item; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
