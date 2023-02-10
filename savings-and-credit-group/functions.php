<?php
/**
 * Plugin Name: Savings and Credit Group
 * Plugin URI:  https://sharebility.net/savings-and-credit-group-plugin
 * Description: A plugin for managing savings and credit group records.
 * Version:     1.0.0
 * Author:      Sharebility Uganda Limited
 * Author URI:  https://sharebility.net
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: savings-and-credit-group
 */

// 1st bit starts here
add_action( 'init', 'scg_register_post_types' );

function scg_register_post_types() {
    $labels = array(
        'name'               => _x( 'Group Types', 'post type general name', 'scg' ),
        'singular_name'      => _x( 'Group Type', 'post type singular name', 'scg' ),
        'menu_name'          => _x( 'Group Types', 'admin menu', 'scg' ),
        'name_admin_bar'     => _x( 'Group Type', 'add new on admin bar', 'scg' ),
        'add_new'            => _x( 'Add New', 'group type', 'scg' ),
        'add_new_item'       => __( 'Add New Group Type', 'scg' ),
        'new_item'           => __( 'New Group Type', 'scg' ),
        'edit_item'          => __( 'Edit Group Type', 'scg' ),
        'view_item'          => __( 'View Group Type', 'scg' ),
        'all_items'          => __( 'All Group Types', 'scg' ),
        'search_items'       => __( 'Search Group Types', 'scg' ),
        'parent_item_colon'  => __( 'Parent Group Types:', 'scg' ),
        'not_found'          => __( 'No group types found.', 'scg' ),
        'not_found_in_trash' => __( 'No group types found in Trash.', 'scg' ),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'scg' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'group-type' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		'menu_icon'			=> 'dashicons-groups'
	);

	register_post_type( 'scg_group_type', $args );
}
add_action( 'init', 'scg_register_post_types' );

add_filter( 'enter_title_here', function( $title ) {
    if ( get_post_type() === 'scg_group_type' ) {
        return __( 'Enter group type name here', 'scg' );
    }
    return $title;
});

// bit ends here
// 2nd bit starts here

// Hook in the metaboxes
add_action( 'add_meta_boxes', 'scg_add_group_metaboxes' );
function scg_add_group_metaboxes() {
// Add metabox to the group post type
add_meta_box(
'scg_group_details_metabox',
__( 'Group Details', 'scg' ),
'scg_render_group_details_metabox',
'scg_group',
'normal',
'default'
);
// Add metabox to the loan post type
add_meta_box(
	'scg_loan_details_metabox',
	__( 'Loan Details', 'scg' ),
	'scg_render_loan_details_metabox',
	'scg_loan',
	'normal',
	'default'
);
}

// Render the group details metabox
function scg_render_group_details_metabox( $post ) {
// Get the group members
$group_members = get_post_meta( $post->ID, '_scg_group_members', true );
?>
<table class="form-table">
<tr>
<th><label for="scg_group_members"><?php _e( 'Group Members', 'scg' ); ?></label></th>
<td>
<textarea name="scg_group_members" id="scg_group_members" rows="5" cols="30"><?php echo esc_textarea( $group_members ); ?></textarea>
<p class="description"><?php _e( 'Enter the names of the group members, separated by line breaks.', 'scg' ); ?></p>
</td>
</tr>
</table>
<?php
}

// Render the loan details metabox
function scg_render_loan_details_metabox( $post ) {
// Get the loan amount
$loan_amount = get_post_meta( $post->ID, '_scg_loan_amount', true );
// Get the loan repayment date
$loan_repayment_date = get_post_meta( $post->ID, '_scg_loan_repayment_date', true );
?>
<table class="form-table">
	<tr>
		<th><label for="scg_loan_amount"><?php _e( 'Loan Amount', 'scg' ); ?></label></th>
		<td>
			<input type="text" name="scg_loan_amount" id="scg_loan_amount" value="<?php echo esc_attr( $loan_amount ); ?>">
		</td>
	</tr>
	<tr>
		<th><label for="scg_loan_repayment_date"><?php _e( 'Repayment Date', 'scg' ); ?></label></th> to <td><input type="date" id="scg_loan_repayment_date" name="scg_loan_repayment_date" value="<?php echo esc_attr( $repayment_date ); ?>" /></td>
</tr>
<tr class="form-field">
<th><label for="scg_loan_status"><?php _e( 'Loan Status', 'scg' ); ?></label></th>
<td>
    <select id="scg_loan_status" name="scg_loan_status">
        <option value="pending" <?php selected( $status, 'pending' ); ?>><?php _e( 'Pending', 'scg' ); ?></option>
        <option value="approved" <?php selected( $status, 'approved' ); ?>><?php _e( 'Approved', 'scg' ); ?></option>
        <option value="rejected" <?php selected( $status, 'rejected' ); ?>><?php _e( 'Rejected', 'scg' ); ?></option>
        <option value="closed" <?php selected( $status, 'closed' ); ?>><?php _e( 'Closed', 'scg' ); ?></option>
    </select>
</td>
</tr>
</table>
<?php
}
// 3rd bit starts here
add_action( 'add_meta_boxes', 'scg_add_meta_boxes' );

function scg_add_meta_boxes() {
    add_meta_box(
        'scg_loan_details',
        __( 'Loan Details', 'scg' ),
        'scg_loan_details_meta_box_callback',
        'scg_loan',
        'normal',
        'default'
    );
}

function scg_loan_details_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'scg_loan_details_nonce' );
    $loan_details = get_post_meta( $post->ID );
    ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="scg_loan_amount"><?php _e( 'Loan Amount', 'scg' ); ?></label></th>
                <td><input type="number" id="scg_loan_amount" name="scg_loan_amount" value="<?php if ( isset ( $loan_details['scg_loan_amount'] ) ) echo esc_attr( $loan_details['scg_loan_amount'][0] ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="scg_loan_repayment_date"><?php _e( 'Repayment Date', 'scg' ); ?></label></th>
                <td><input type="date" id="scg_loan_repayment_date" name="scg_loan_repayment_date" value="<?php if ( isset ( $loan_details['scg_loan_repayment_date'] ) ) echo esc_attr( $loan_details['scg_loan_repayment_date'][0] ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="scg_loan_interest_rate"><?php _e( 'Interest Rate', 'scg' ); ?></label></th>
                <td><input type="number" id="scg_loan_interest_rate" name="scg_loan_interest_rate" step="0.01" value="<?php if ( isset ( $loan_details['scg_loan_interest_rate'] ) ) echo esc_attr( $loan_details['scg_loan_interest_rate'][0] );
				?>" /></td>
</tr>
</tbody>
</table>
<?php
}
// 4th bit starts here
// Define meta box for the SCG loans
function scg_add_meta_boxes() {
    add_meta_box(
        'scg_loan_details',
        __( 'Loan Details', 'scg' ),
        'scg_render_loan_details_meta_box',
        'scg_loan',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'scg_add_meta_boxes' );

// Render the Loan Details meta box
function scg_render_loan_details_meta_box( $post ) {
    // Use nonce for verification
    wp_nonce_field( basename( __FILE__ ), 'scg_loan_details_nonce' );

    // Get the loan data if it's already been entered
    $loan_amount = get_post_meta( $post->ID, '_scg_loan_amount', true );
    $repayment_date = get_post_meta( $post->ID, '_scg_loan_repayment_date', true );

    // Output the field
    ?>
    <table class="form-table">
        <tr>
            <th><label for="scg_loan_amount"><?php _e( 'Loan Amount', 'scg' ); ?></label></th>
            <td><input type="number" id="scg_loan_amount" name="scg_loan_amount" value="<?php echo esc_attr( $loan_amount ); ?>" /></td>
        </tr>
        <tr>
            <th><label for="scg_loan_repayment_date"><?php _e( 'Repayment Date', 'scg' ); ?></label></th>
            <td><input type="date" id="scg_loan_repayment_date" name="scg_loan_repayment_date" value="<?php echo esc_attr( $repayment_date ); ?>" /></td>
        </tr>
    </table>
    <?php
}
// bit ends here
// 5th bit starts here
// Register the meta boxes for the loan post type
function scg_add_meta_boxes() {
    add_meta_box(
        'scg_loan_details_meta_box',
        __( 'Loan Details', 'scg' ),
        'scg_loan_details_meta_box_callback',
        'scg_loan',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'scg_add_meta_boxes' );

// Render the loan details meta box
function scg_loan_details_meta_box_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'scg_loan_details_meta_box_nonce' );

    $loan_repayment_date = get_post_meta( $post->ID, '_scg_loan_repayment_date', true );
    $loan_amount = get_post_meta( $post->ID, '_scg_loan_amount', true );
    ?>
    <table class="form-table">
        <tr>
            <th><label for="scg_loan_repayment_date"><?php _e( 'Repayment Date', 'scg' ); ?></label></th>
            <td><input type="date" name="scg_loan_repayment_date" id="scg_loan_repayment_date" value="<?php echo esc_attr( $loan_repayment_date ); ?>"></td>
        </tr>
        <tr>
            <th><label for="scg_loan_amount"><?php _e( 'Loan Amount', 'scg' ); ?></label></th>
            <td><input type="text" name="scg_loan_amount" id="scg_loan_amount" value="<?php echo esc_attr( $loan_amount ); ?>"></td>
        </tr>
    </table>
    <?php
}

// Save the loan details meta box data
function scg_save_loan_details_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['scg_loan_details_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['scg_loan_details_meta_box_nonce'], basename( __FILE__ ) ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $loan_repayment_date = sanitize_text_field( $_POST['scg_loan_repayment_date'] );
$loan_amount = (float) sanitize_text_field( $_POST['scg_loan_amount'] );
$loan_interest_rate = (float) sanitize_text_field( $_POST['scg_loan_interest_rate'] );
$loan_repayment_term = (int) sanitize_text_field( $_POST['scg_loan_repayment_term'] );

// Store the loan details as post meta data
update_post_meta( $post_id, 'scg_loan_repayment_date', $loan_repayment_date );
update_post_meta( $post_id, 'scg_loan_amount', $loan_amount );
update_post_meta( $post_id, 'scg_loan_interest_rate', $loan_interest_rate );
update_post_meta( $post_id, 'scg_loan_repayment_term', $loan_repayment_term );
}

// Add action to save loan details
add_action( 'save_post_loan', 'scg_save_loan_details' );

// Add filter to display loan details on the front-end
function scg_display_loan_details( $content ) {

// Get the loan post ID
$post_id = get_the_ID();

// Get the loan details
$loan_repayment_date = get_post_meta( $post_id, 'scg_loan_repayment_date', true );
$loan_amount = get_post_meta( $post_id, 'scg_loan_amount', true );
$loan_interest_rate = get_post_meta( $post_id, 'scg_loan_interest_rate', true );
$loan_repayment_term = get_post_meta( $post_id, 'scg_loan_repayment_term', true );

// Display the loan details
$content .= '<table>';
$content .= '<tr><th>' . __( 'Repayment Date', 'scg' ) . '</th><td>' . $loan_repayment_date . '</td></tr>';
$content .= '<tr><th>' . __( 'Loan Amount', 'scg' ) . '</th><td>' . $loan_amount . '</td></tr>';
$content .= '<tr><th>' . __( 'Interest Rate', 'scg' ) . '</th><td>' . $loan_interest_rate . '</td></tr>';
$content .= '<tr><th>' . __( 'Repayment Term', 'scg' ) . '</th><td>' . $loan_repayment_term . '</td></tr>';
$content .= '</table>';

return $content;
}

add_filter( 'the_content', 'scg_display_loan_details' );

function scg_display_loan_details( $content ) {
    global $post;
    if ( 'scg_loan' != $post->post_type ) {
        return $content;
    }
    $loan_amount = get_post_meta( $post->ID, 'scg_loan_amount', true );
    $loan_repayment_date = get_post_meta( $post->ID, 'scg_loan_repayment_date', true );
    $loan_details = '<p><strong>Loan Amount:</strong> ' . $loan_amount . '</p>';
    $loan_details .= '<p><strong>Repayment Date:</strong> ' . $loan_repayment_date . '</p>';
    return $content . $loan_details;
}
//5th bit ends here
// 6th bit starts here
function scg_display_group_details( $content ) {
if ( is_singular( 'scg_group' ) ) {
$group_id = get_the_ID();
$members = get_post_meta( $group_id, 'scg_group_members', true );
$group_type = get_post_meta( $group_id, 'scg_group_type', true );
$group_details = '';
$group_details .= '<h3>' . __( 'Group Details', 'scg' ) . '</h3>';
$group_details .= '<table class="group-details">';
$group_details .= '<tr><th>' . __( 'Group Type', 'scg' ) . ':</th><td>' . $group_type . '</td></tr>';
$group_details .= '<tr><th>' . __( 'Members', 'scg' ) . ':</th><td>' . $members . '</td></tr>';
$group_details .= '</table>';
return $content . $group_details;
}
return $content;
}
add_filter( 'the_content', 'scg_display_group_details' );
// 6th bit ends here

// 7th bit starts here

/**
 * Display Loan Details
 *
 * @param string $content Post content.
 *
 * @return string
 */
function scg_display_loan_details( $content ) {
	if ( 'loan' !== get_post_type() ) {
		return $content;
	}

	$loan_amount       = get_post_meta( get_the_ID(), 'scg_loan_amount', true );
	$loan_repayment_date = get_post_meta( get_the_ID(), 'scg_loan_repayment_date', true );

	$loan_details = '<h2>Loan Details:</h2>';
	$loan_details .= '<table>';
	$loan_details .= '<tr><th>Loan Amount:</th><td>' . esc_html( $loan_amount ) . '</td></tr>';
	$loan_details .= '<tr><th>Repayment Date:</th><td>' . esc_html( $loan_repayment_date ) . '</td></tr>';
	$loan_details .= '</table>';

	return $loan_details . $content;
}

add_filter( 'the_content', 'scg_display_loan_details' );

// 7th bit ends here


?>