<div class="wrap">
    <h2>
        <?php esc_html_e( 'Trew Knowledge Social Login', 'tksl' ); ?>
    </h2>

    <form action="options.php" method="POST">
      <?php echo $settings; ?>
    </form>

    <?php if ( $signature ): ?>
        <hr>
        <p>
            <em>
                <?= sprintf(
                    _x('The GDPR Framework. Built with &#9829; by %sTrew Knowledge%s.', '(Admin)', 'tksl'),
                    '<a href="https://trewknowledge.com/" target="_blank">',
                        '</a>'
                ); ?>
                &nbsp;
                |
                &nbsp;
                <?= sprintf(
                    _x("Support our development efforts! %sDonate%s or leave a %s5-star rating%s.", '(Admin)', 'tksl'),
                    '<a href="https://trewknowledge.com/tksl/donate/" target="_blank">',
                    '</a>',
                    '<a href="https://wordpress.org/plugins/tksl/#reviews" target="_blank">',
                    '</a>'
                ); ?>
            </em>
        </p>
    <?php endif; ?>
</div>
