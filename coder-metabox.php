<?php 

class CoderMetaBox{
	public $id;
	public $type; 
	public $name; 
	public $coder_meta_fields; 
	public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_custom_meta_box'] );
        add_action('save_post',[$this, 'save_custom_meta'] );
    }
 
	public function add_custom_meta_box() {
	    add_meta_box(
	        $this->id , // $id
	        $this->name, // $title 
	        [$this, 'show_custom_meta_box'], // $callback
	        $this->type, // $page
	        'normal', // $ position
			'high',
			null
		);  
	}
 
	// The Callback
	function show_custom_meta_box() {
		global $post;
		$coder_meta_fields = $this->coder_meta_fields;
		// Use nonce for verification
		echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
		 
		// Begin the field table and loop
		echo '<table class="form-table">';
		foreach ($coder_meta_fields as $field) {
			// get value of this field if it exists for this post
			$meta = get_post_meta($post->ID, $field['id'], true);
			// start a table row
			echo '<tr>
					<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
					<td>';
					switch($field['type']) {
						// text
						case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
						break;

						// textarea
						case 'textarea':
							echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
								<br /><span class="description">'.$field['desc'].'</span>';
						break;

						// checkbox
						case 'checkbox':
							echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
								<label for="'.$field['id'].'">'.$field['desc'].'</label>';
						break;

						// select
						case 'select':
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
							foreach ($field['options'] as $option) {
								echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
							}
							echo '</select><br /><span class="description">'.$field['desc'].'</span>';
						break;
					} //end switch
			echo '</td></tr>';
		} // end foreach
		echo '</table>'; // End table
	}

	function save_custom_meta($post_id) {
	    $coder_meta_fields = $this->coder_meta_fields;

	    // verify nonce
	    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) 
	        return $post_id;
	    // check autosave
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	        return $post_id;
	    // check permissions
	    if ('page' == $_POST['post_type']) {
	        if (!current_user_can('edit_page', $post_id))
	            return $post_id;
	        } elseif (!current_user_can('edit_post', $post_id)) {
	            return $post_id;
	    }
	     
	    // loop  for fields and save the data
	    foreach ($coder_meta_fields as $field) {
	        $old = get_post_meta($post_id, $field['id'], true);
	        $new = $_POST[$field['id']];
	        if ($new && $new != $old) {
	            update_post_meta($post_id, $field['id'], $new);
	        } elseif ('' == $new && $old) {
	            delete_post_meta($post_id, $field['id'], $old);
	        }
	    } // end foreach
	}
 
}
// Get Output Meta Data
function get_coder_field($id){
	$post_id = get_the_ID();
   	$metadata = get_post_meta( $post_id, $id, true );
   	return $metadata;
}
// Get Output Meta Data
function the_coder_field($id){
	$post_id = get_the_ID();
   	$metadata = get_post_meta( $post_id, $id, true );
   	echo $metadata;
}
