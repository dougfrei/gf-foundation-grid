<?php
class GFFoundationColumn extends GF_Field
{
	public static $column_open = false;
	
	public $type = 'foundation-column';

	public function get_form_editor_field_title()
	{
		return esc_attr__('Foundation Column', 'gravityforms');
	}

	public function is_conditional_logic_supported()
	{
		return false;
	}

	public function get_form_editor_field_settings()
	{
		return array(
			'column_description',
			'css_class_setting'
		);
	}

	public function get_field_input($form, $value = '', $entry = null)
	{
		return '';
	}

	public function get_field_content($value, $force_frontend_label, $form)
	{
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor = $this->is_form_editor();
		$is_admin = $is_entry_detail || $is_form_editor;

		if ($is_admin) {
			$admin_buttons = $this->get_admin_buttons();
			
			return $admin_buttons.'<label class=\'gfield_label\'>'.$this->get_form_editor_field_title().'</label>{FIELD}<hr>';
		}

		return '';
	}
}
