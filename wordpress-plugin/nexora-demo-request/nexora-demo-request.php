<?php
/**
 * Plugin Name: Nexora Demo Request
 * Description: Adds a "Request a Demo" form and saves submissions in WordPress.
 * Version: 1.0.0
 * Author: Nexora
 * Text Domain: nexora-demo-request
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// set up the demo requests post type.
function nexoraDemoReq_register_post_type() {
    $labels = array(
        'name'          => __( 'Demo Requests', 'nexora-demo-request' ),
        'singular_name' => __( 'Demo Request', 'nexora-demo-request' ),
        'menu_name'     => __( 'Demo Requests', 'nexora-demo-request' ),
        'all_items'     => __( 'All Requests', 'nexora-demo-request' ),
        'search_items'  => __( 'Search Requests', 'nexora-demo-request' ),
        'not_found'     => __( 'No demo requests yet.', 'nexora-demo-request' ),
    );

    register_post_type( 'nexora_demo_request', array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-email-alt',
        'menu_position'       => 25,
        'supports'            => array( 'title' ),
        'capabilities'        => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap'        => true,
    ) );
}
add_action( 'init', 'nexoraDemoReq_register_post_type' );



// store the form data and errors for later use.
function nexoraDemoReq_state( $new = null ) {
    static $state = array(
        'values' => array( 'name' => '', 'email' => '', 'company' => '', 'message' => '' ),
        'errors' => array(),
    );
    if ( null !== $new ) {
        $state = $new;
    }
    return $state;
}

// form submission logic
function nexoraDemoReq_handle_submission() {
    if ( empty( $_POST['nexoraDemoReq_nonce'] ) ) {
        return;
    }

    $state  = nexoraDemoReq_state();
    $errors = array();

    $nonce = sanitize_text_field( wp_unslash( $_POST['nexoraDemoReq_nonce'] ) );
    if ( ! wp_verify_nonce( $nonce, 'nexoraDemoReq_submit' ) ) {
        $errors['form'] = __( 'Security check failed. Please reload the page and try again.', 'nexora-demo-request' );
        nexoraDemoReq_state( array( 'values' => $state['values'], 'errors' => $errors ) );
        return;
    }

    $values = array(
        'name'    => isset( $_POST['nexora_name'] ) ? sanitize_text_field( wp_unslash( $_POST['nexora_name'] ) ) : '',
        'email'   => isset( $_POST['nexora_email'] ) ? sanitize_email( wp_unslash( $_POST['nexora_email'] ) ) : '',
        'company' => isset( $_POST['nexora_company'] ) ? sanitize_text_field( wp_unslash( $_POST['nexora_company'] ) ) : '',
        'message' => isset( $_POST['nexora_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['nexora_message'] ) ) : '',
    );

    if ( '' === $values['name'] ) {
        $errors['name'] = __( 'Please enter your name.', 'nexora-demo-request' );
    } elseif ( mb_strlen( $values['name'] ) > 100 ) {
        $errors['name'] = __( 'Name must be 100 characters or fewer.', 'nexora-demo-request' );
    }

    if ( '' === $values['email'] || ! is_email( $values['email'] ) ) {
        $errors['email'] = __( 'Please enter a valid email address.', 'nexora-demo-request' );
    }

    if ( '' === $values['company'] ) {
        $errors['company'] = __( 'Please enter your company name.', 'nexora-demo-request' );
    }

    if ( mb_strlen( $values['message'] ) < 10 ) {
        $errors['message'] = __( 'Please write at least 10 characters.', 'nexora-demo-request' );
    } elseif ( mb_strlen( $values['message'] ) > 2000 ) {
        $errors['message'] = __( 'Message must be 2000 characters or fewer.', 'nexora-demo-request' );
    }

    if ( ! empty( $errors ) ) {
        $errors['form'] = __( 'Please fix the errors below.', 'nexora-demo-request' );
        nexoraDemoReq_state( array( 'values' => $values, 'errors' => $errors ) );
        return;
    }

    $post_id = wp_insert_post( wp_slash( array(
        'post_type'   => 'nexora_demo_request',
        'post_status' => 'publish',
        'post_title'  => $values['name'],
        'meta_input'  => array(
            '_nexora_name'    => $values['name'],
            '_nexora_email'   => $values['email'],
            '_nexora_company' => $values['company'],
            '_nexora_message' => $values['message'],
        ),
    ) ) );

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        $errors['form'] = __( 'Sorry, we could not save your request. Please try again.', 'nexora-demo-request' );
        nexoraDemoReq_state( array( 'values' => $values, 'errors' => $errors ) );
        return;
    }

    $url = get_permalink();
    if ( ! $url ) {
        $url = home_url( '/' );
    }
    wp_safe_redirect( add_query_arg( 'demo', 'sent', $url ) );
    exit;
}
add_action( 'template_redirect', 'nexoraDemoReq_handle_submission' );



// shortcode: [nexora_demo_form] and prints the form.
function nexoraDemoReq_shortcode() {
    $state  = nexoraDemoReq_state();
    $values = $state['values'];
    $errors = $state['errors'];

    if ( isset( $_GET['demo'] ) && 'sent' === sanitize_key( wp_unslash( $_GET['demo'] ) ) ) {
        return '<div class="nexoraDemoReq__success" role="status"><p>'
            . esc_html__( 'Thank you! We have received your request and will contact you within one working day.', 'nexora-demo-request' )
            . '</p></div>';
    }

    ob_start();


    ?>

    <form class="nexoraDemoReq" method="post" action="" novalidate>
        <?php wp_nonce_field( 'nexoraDemoReq_submit', 'nexoraDemoReq_nonce' ); ?>

                <?php if ( isset( $errors['form'] ) ) : ?>
            <p class="nexoraDemoReq__error nexoraDemoReq__error--form" role="alert"><?php echo esc_html( $errors['form'] ); ?></p>
        <?php endif; ?>

        <div class="nexoraDemoReq__group">
            <label for="nexoraDemoReq-name"><?php esc_html_e( 'Full name', 'nexora-demo-request' ); ?></label>
            <input type="text" id="nexoraDemoReq-name" name="nexora_name"
                   value="<?php echo esc_attr( $values['name'] ); ?>"
                   autocomplete="name" required>
            <?php if ( isset( $errors['name'] ) ) : ?>
                <p class="nexoraDemoReq__error"><?php echo esc_html( $errors['name'] ); ?></p>
            <?php endif; ?>
        </div>

        <div class="nexoraDemoReq__group">
            <label for="nexoraDemoReq-email"><?php esc_html_e( 'Work email', 'nexora-demo-request' ); ?></label>
            <input type="email" id="nexoraDemoReq-email" name="nexora_email"
                   value="<?php echo esc_attr( $values['email'] ); ?>"
                   autocomplete="email" required>
            <?php if ( isset( $errors['email'] ) ) : ?>
                <p class="nexoraDemoReq__error"><?php echo esc_html( $errors['email'] ); ?></p>
            <?php endif; ?>
        </div>

        <div class="nexoraDemoReq__group">
            <label for="nexoraDemoReq-company"><?php esc_html_e( 'Company', 'nexora-demo-request' ); ?></label>
            <input type="text" id="nexoraDemoReq-company" name="nexora_company"
                   value="<?php echo esc_attr( $values['company'] ); ?>"
                   autocomplete="organization" required>
            <?php if ( isset( $errors['company'] ) ) : ?>
                <p class="nexoraDemoReq__error"><?php echo esc_html( $errors['company'] ); ?></p>
            <?php endif; ?>
        </div>

        <div class="nexoraDemoReq__group">
            <label for="nexoraDemoReq-message"><?php esc_html_e( 'What would you like to see?', 'nexora-demo-request' ); ?></label>
            <textarea id="nexoraDemoReq-message" name="nexora_message" rows="5" required><?php
                echo esc_textarea( $values['message'] );
            ?></textarea>
            <?php if ( isset( $errors['message'] ) ) : ?>
                <p class="nexoraDemoReq__error"><?php echo esc_html( $errors['message'] ); ?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="nexoraDemoReq__button">
            <?php esc_html_e( 'Request demo', 'nexora-demo-request' ); ?>
        </button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'nexora_demo_form', 'nexoraDemoReq_shortcode' );



// add email and company columns to the admin list
function nexoraDemoReq_admin_columns( $columns ) {
    return array(
        'cb'              => $columns['cb'],
        'title'           => __( 'Request', 'nexora-demo-request' ),
        'nexora_email'    => __( 'Email', 'nexora-demo-request' ),
        'nexora_company'  => __( 'Company', 'nexora-demo-request' ),
        'date'            => __( 'Submitted', 'nexora-demo-request' ),
    );
}
add_filter( 'manage_nexora_demo_request_posts_columns', 'nexoraDemoReq_admin_columns' );

function nexoraDemoReq_admin_column_content( $column, $post_id ) {
    if ( 'nexora_email' === $column ) {
        $email = get_post_meta( $post_id, '_nexora_email', true );
        if ( $email ) {
            echo '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>';
        }
    }

    if ( 'nexora_company' === $column ) {
        echo esc_html( get_post_meta( $post_id, '_nexora_company', true ) );
    }
}
add_action( 'manage_nexora_demo_request_posts_custom_column', 'nexoraDemoReq_admin_column_content', 10, 2 );

// to show the saved request details on the edit screen.
function nexoraDemoReq_add_meta_box() {
    add_meta_box(
        'nexoraDemoReq_details',
        __( 'Request details', 'nexora-demo-request' ),
        'nexoraDemoReq_render_meta_box',
        'nexora_demo_request',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nexoraDemoReq_add_meta_box' );

function nexoraDemoReq_render_meta_box( $post ) {
    $name    = get_post_meta( $post->ID, '_nexora_name', true );
    $email   = get_post_meta( $post->ID, '_nexora_email', true );
    $company = get_post_meta( $post->ID, '_nexora_company', true );
    $message = get_post_meta( $post->ID, '_nexora_message', true );
    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><?php esc_html_e( 'Full name', 'nexora-demo-request' ); ?></th>
            <td><?php echo esc_html( $name ); ?></td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Email', 'nexora-demo-request' ); ?></th>
            <td><a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a></td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Company', 'nexora-demo-request' ); ?></th>
            <td><?php echo esc_html( $company ); ?></td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Message', 'nexora-demo-request' ); ?></th>
            <td><?php echo nl2br( esc_html( $message ) ); ?></td>
        </tr>
    </table>
    <?php
}


// to apply styling to the page that contains shortcode.
function nexoraDemoReq_enqueue_assets() {
    global $post;

    if ( is_singular() && $post instanceof WP_Post && has_shortcode( $post->post_content, 'nexora_demo_form' ) ) {
        wp_enqueue_style(
            'nexoraDemoReq-form',
            plugin_dir_url( __FILE__ ) . 'assets/style.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_style(
            'nexoraDemoReq-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap',
            array(),
            null
        );
    }
}
add_action( 'wp_enqueue_scripts', 'nexoraDemoReq_enqueue_assets' );
