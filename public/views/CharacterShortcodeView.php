<div id="rick-morty-character-search">
    <form id="rick-morty-character-form" action="#" method="post">
        <div class="form-field">
            <label for="name-search">Search by name:</label>
            <input type="text" id="name-search" name="name" placeholder="Enter name...">
        </div>
        <div class="form-field">
            <label for="species-search">Search by species:</label>
            <select id="species-search" name="species">
                <option value="">
                    <?php echo __('All Species', RICK_MORTY_TEXT_DOMAIN); ?>
                </option>
                <?php foreach ($species_options as $species): ?>
                    <option value="<?php echo esc_attr($species); ?>">
                        <?php echo esc_html($species); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Search</button>
    </form>
    <div id="rick-morty-character-list">
        <?php if (!empty($initial_posts)): ?>
            <?php foreach ($initial_posts as $post): ?>
                <div class="entry-card">
                    <?php if (!empty($post['meta']['image'])): ?>
                        <img src="<?php echo esc_url($post['meta']['image']); ?>" alt="<?php echo esc_attr($post['title']); ?>" class="entry-card-image">
                    <?php endif; ?>
                    <div class="entry-card-details">
                        <h2 class="entry-card-title"><?php echo esc_html($post['title']); ?></h2>
                        <?php if (!empty($post['meta']['species'])): ?>
                            <p class="entry-card-meta"><?php echo esc_html($post['meta']['species']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($post['meta']['status'])): ?>
                            <p class="entry-card-meta"><?php echo esc_html($post['meta']['status']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($post['meta']['type'])): ?>
                            <p class="entry-card-meta"><?php echo esc_html($post['meta']['type']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($post['meta']['gender'])): ?>
                            <p class="entry-card-meta"><?php echo esc_html($post['meta']['gender']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>            
        <?php endif; ?>
    </div>
    <div id="rick-morty-search-notice" class="notice notice-info">
        <?php if (empty($initial_posts)): ?>
                No characters found.
        <?php endif; ?> 
     </div>  
    <button id="rick-morty-load-more" data-page="2"
        <?php if (empty($initial_posts)): ?>
            style="display: none;"
        <?php endif; ?>
    >Load More</button> 
</div>
