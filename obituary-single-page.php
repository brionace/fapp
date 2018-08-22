<?php
//Single obituaries
remove_action('genesis_loop', 'genesis_do_loop');
add_action('genesis_loop', 'my_custom_loop');

function my_custom_loop()
{
    global $post;

    $post_type_singular = 'obituary';
    $post_type_plural = 'obituaries';

    // just giving
    $charity_id_one = get_post_meta($post->ID, $post_type_plural . '_charity_id_one', true);
    $charity_id_two = get_post_meta($post->ID, $post_type_plural . '_charity_id_two', true);

    $charity_name_one = get_post_meta($post->ID, $post_type_plural . '_charity_name_one', true);
    $charity_name_two = get_post_meta($post->ID, $post_type_plural . '_charity_name_two', true);

    $just_giving_uri = 'https://www.justgiving.com/4w350m3/donation/direct/charity/';
    $exit_url = urlencode(site_url() . '/?page_id=13952&post_id=' . $post->ID . '&donation_id=JUSTGIVING-DONATION-ID');

    // http://www.gseller.co.uk/?page_id=13952&post_id=31004&donation_id=1012936902 - link for gillian donation

    $all_meta = get_post_meta($post->ID, 'donation');

    $total_one = 0;
    $total_two = 0;
    foreach ($all_meta as $meta) {
        if ($meta['charityId'] == $charity_id_one) {
            $total_one += $meta['amount'];
        } else if ($meta['charityId'] == $charity_id_two) {
            $total_two += $meta['amount'];
        }
    }

    if ($total_one < 1) {
        $total_one = 0;
    }

    if ($total_two < 1) {
        $total_two = 0;
    }

    $born_on = create_date_format(get_post_meta($post->ID, $post_type_plural . '_born_on', true));
    $died = create_date_format(get_post_meta($post->ID, $post_type_plural . '_died', true));

    $funeral_location = nl2br(get_post_meta($post->ID, $post_type_plural . '_funeral_location', true));
    $funeral_date = create_date_format(get_post_meta($post->ID, $post_type_plural . '_funeral_date', true));
    $funeral_time = get_post_meta($post->ID, $post_type_plural . '_funeral_time', true);
    $funeral_postcode = str_replace(' ', '', get_post_meta($post->ID, $post_type_plural . '_funeral_postcode', true));

    $cremation_location = nl2br(get_post_meta($post->ID, $post_type_plural . '_cremation_location', true));
    $cremation_date = create_date_format(get_post_meta($post->ID, $post_type_plural . '_cremation_date', true));
    $cremation_time = get_post_meta($post->ID, $post_type_plural . '_cremation_time', true);
    $cremation_postcode = str_replace(' ', '', get_post_meta($post->ID, $post_type_plural . '_cremation_postcode', true));

    $reception_location = nl2br(get_post_meta($post->ID, $post_type_plural . '_reception_location', true));
    $reception_date = create_date_format(get_post_meta($post->ID, $post_type_plural . '_reception_date', true));
    $reception_time = get_post_meta($post->ID, $post_type_plural . '_reception_time', true);
    $reception_postcode = str_replace(' ', '', get_post_meta($post->ID, $post_type_plural . '_reception_postcode', true));

    $no_donations_accepted = get_post_meta($post->ID, $post_type_plural . '_no_donations', true);

    ?>

    <div class="<?= $post_type_singular; ?>">

        <div class="thumbnail one-third first">
            <?= has_post_thumbnail() ? get_the_post_thumbnail($post->ID, '260x260') : '<img src="' . get_stylesheet_directory_uri() . '/images/' . $post_type_singular . '-holder300x300.png">'; ?>
        </div>

        <?php if (!$no_donations_accepted) : ?>
            <?php if ($charity_id_one) : ?>

                <div id="charity-one" class="mfp-hide white-popup-block">
                    <p>Thank you for choosing to make a donation. You will be directed to Just Giving to donate by
                        clicking the ‘Donate Now’ button below.</p>
                    <p>On the Just Giving website, if you tick the checkbox ‘Hide my donation amount from others’, your
                        donation will not appear on the G. Seller & Co. Ltd website. The payment will have been paid to
                        the chosen charity but will not appear on the obituary total.</p>
                    <p>If you leave this checkbox blank, your details will not be shown publicly and your donation will
                        appear on the obituary total of the G. Seller & Co. Ltd website.</p>

                    <p class="text-left"><a class="popup-modal-dismiss" href="#">Return to Obituary</a></p>
                    <p class="text-right"><a class="button" href="<?= $just_giving_uri . $charity_id_one; ?>?amount=10&reference=<?= $post->ID; ?>-gseller-post-id&exitUrl=<?= $exit_url; ?>">Continue
                            to Donate</a></p>
                </div>

                <a href="#charity-one" class="popup-modal">
                    <div class="charity <?= $charity_id_one && $charity_id_two ? 'one-third' : 'two-thirds'; ?>">
                        <div class="header">
                            <p>In partnership with</p>
                            <img src="<?= get_stylesheet_directory_uri(); ?>/images/just-giving-logo.png" alt=""/>
                        </div>
                        <div class="main">
                            <p>Donate in memory of</p>

                            <p><?= strtoupper($post->post_title); ?></p>

                            <p>In aid of</p>

                            <p><?= strtoupper($charity_name_one); ?></p>
                        </div>
                        <div class="footer">
                            <p>Total: &pound;<?= number_format($total_one, 2); ?></p>
                        </div>
                    </div>
                </a>

                <?php if ($charity_id_two) : ?>

                    <div id="charity-two" class="mfp-hide white-popup-block">
                        <p>Thank you for choosing to make a donation. You will be directed to Just Giving to donate by
                            clicking the ‘Donate Now’ button below.</p>
                        <p>On the Just Giving website, if you tick the checkbox ‘Hide my donation amount from others’, your
                            donation will not appear on the G. Seller & Co. Ltd website. The payment will have been paid to
                            the chosen charity but will not appear on the obituary total.</p>
                        <p>If you leave this checkbox blank, your details will not be shown publicly and your donation will
                            appear on the obituary total of the G. Seller & Co. Ltd website.</p>

                        <p class="text-left"><a class="popup-modal-dismiss" href="#">Return to Obituary</a></p>
                        <p class="text-right"><a class="button" href="<?= $just_giving_uri . $charity_id_two; ?>?amount=10&reference=<?= $post->ID; ?>-gseller-post-id&exitUrl=<?= $exit_url; ?>">Continue
                                to Donate</a></p>
                    </div>

                    <a href="#charity-two" class="popup-modal">
                        <div class="charity one-third">
                            <div class="header">
                                <p>In partnership with</p>
                                <img src="<?= get_stylesheet_directory_uri(); ?>/images/just-giving-logo.png" alt=""/>
                            </div>
                            <div class="main">
                                <p>Donate in memory of</p>

                                <p><?= strtoupper($post->post_title); ?></p>

                                <p>In aid of</p>

                                <p><?= strtoupper($charity_name_two); ?></p>
                            </div>
                            <div class="footer">
                                <p>Total: &pound;<?= number_format($total_two, 2); ?></p>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>

            <?php else: ?>
                <div class="charity two-thirds default">
                    <div class="header">
                        <p>Donations may be made in memory of</p>

                        <p class="brand-primary-color"><?= strtoupper($post->post_title); ?></p>
                    </div>
                    <div class="main">
                        <p>Please make cheques payable to the ‘Client Charitable Donations Fund’</p>

                        <p>C/O G. Seller & Co Ltd, 75 Upper Bond Street, Hinckley, LE10 1RH</p>

                        <p>For BACS payments - Account Number: 17141982 Sort Code: 60-11-06</p>

                        <p>Please provide your name, who the donation is sent on behalf of* and an email address for
                            receipting purposes. Monies will be forwarded to the families chosen charities.</p>

                        <p>*A list of donators is sent directly to the family 4 weeks after the date of the funeral</p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="information">
            <h1 class="page-title"><?= $post->post_title; ?></h1>

            <p><b>(<?= $born_on; ?> - <?= $died; ?>)</b></p>
            <?= wpautop($post->post_content); ?>
        </div>

    </div>

    <div class="two-thirds first">
        <div id="tabs" class="obituaries">
            <ul>
                <li><a href="#condolence" onclick="comment_type('');">Condolences &amp; Stories</a></li>
                <li><a href="#candle" onclick="comment_type('candle');">Light a Candle</a></li>
                <li><a href="#attend" onclick="comment_type('attendance');">Attendance
                        (<?= get_attendance_count('attendance'); ?>)</a></li>
            </ul>
            <div id="condolence">
                <div class="action">
                    <a href="#respond" class="button">Add a Message</a>
                </div>
                <div class="display-comments">
                    <?php get_custom_comments(); ?>
                </div>
            </div>
            <div id="candle">
                <div class="action">
                    <a href="#respond" class="button">Light a Candle</a>
                </div>
                <div class="display-comments">
                    <?php get_custom_comments('candle'); ?>
                </div>
            </div>
            <div id="attend">
                <div class="action">
                    <a href="#respond" class="button">Attend</a>
                </div>
                <div class="display-comments">
                    <?php get_custom_comments('attendance'); ?>
                </div>
            </div>
            <?php
            $args = array(
                'title_reply' => 'Leave a Condolence Message'
            );
            $id = get_the_ID();
            comment_form($args, $id);
            ?>
        </div>

    </div>

    <div class="one-third">

        <?php
        $details = array(
            'funeral' => array(
                'title' => 'Funeral Service',
                'location' => $funeral_location,
                'date' => $funeral_date,
                'time' => $funeral_time,
                'postcode' => $funeral_postcode
            ),
            'cremation' => array(
                'title' => 'Committal Details',
                'location' => $cremation_location,
                'date' => $cremation_date,
                'time' => $cremation_time,
                'postcode' => $cremation_postcode
            ),
            'reception' => array(
                'title' => 'Reception Details',
                'location' => $reception_location,
                'date' => $reception_date,
                'time' => $reception_time,
                'postcode' => $reception_postcode
            )
        );

        foreach ($details as $key => $value) { ?>
            <?php if ($value['location'] || $value['date'] || $value['time'] || $value['postcode']): ?>
                <div class="<?= $key; ?>-information map-information">
                    <h2><?= $value['title']; ?></h2>

                    <?php if ($value['location']): ?>
                        <p><b>Location:</b><br/>
                            <?= $value['location']; ?></p>
                    <?php endif; ?>

                    <?php if ($value['date'] || $value['time']): ?>
                        <p><?php if ($value['date']): ?><b>Date:</b> <?= $value['date']; ?><br/><?php endif; ?>
                            <?php if ($value['time']): ?><b>Time:</b> <?= $value['time']; ?><?php endif; ?></p>
                    <?php endif; ?>

                    <?php if ($value['postcode']): ?>
                        <input name="<?= $key; ?>-address" id="<?= $key; ?>-address" value="<?= $value['postcode']; ?>"
                               type="hidden">
                        <div id="<?= $key; ?>-map"></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php
        }
        ?>
    </div>

    <?php
}

genesis();
