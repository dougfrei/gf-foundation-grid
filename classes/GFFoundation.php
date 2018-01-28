<?php
class GFFoundation
{
	static protected $row_open = false;
	static protected $col_open = false;


	static public function init()
	{
		require_once('GFFoundationRow.php');
		require_once('GFFoundationColumn.php');

		GF_Fields::register(new GFFoundationRow());
		GF_Fields::register(new GFFoundationColumn());

		// A normal static method callback in form of "array('GFFoundation', 'static_method')"
		// is not possible here since Gravity Forms uses a custom way of processing filters
		// that causes an incompatibility
		add_filter('gform_field_container', function($field_container, $field, $form, $css_class, $style, $field_content) {
			if (is_admin()) {
				return $field_container;
			}

			$return_html = '';
	
			switch ($field['type']) {
				case 'foundation-row':
					$return_html = (__CLASS__)::process_row($field);
					break;
	
				case 'foundation-column':
					$return_html = (__CLASS__)::process_column($form, $field);
					break;
	
				default:
					$return_html = $field_container;
					break;
			}
	
			return $return_html;
		}, 10, 6);

		add_filter('gform_get_form_filter', function($form_string, $form) {
			$closing_div_count = 0;

			if ((__CLASS__)::$row_open) {
				$closing_div_count++;
			}

			if ((__CLASS__)::$col_open) {
				$closing_div_count++;
			}

			return str_replace("<div class='gform_footer", str_repeat('</div>', $closing_div_count)."<div class='gform_footer", $form_string);
		}, 10, 2);

		// add_action('gform_field_standard_settings', function($placement, $form_id) {
		// 	error_log($placement);

		// 	if ($placement == 0) {
		// 		$description = 'Column breaks should be placed between fields to split form into separate columns. You do not need to place any column breaks at the beginning or end of the form, only in the middle.';
		// 		echo '<li class="column_description field_setting">'.$description.'</li>';
		// 	}
		// }, 10, 2);
	}

	static protected function process_row($field)
	{
		// error_log(sprintf('process_row [row_open = %d | col_open = %d]', self::$row_open, self::$col_open));

		$row_html = ''; // end the previous gform ul element

		if (self::$row_open) {
			if (self::$col_open) {
				$row_html .= '</ul></div>'; // add an additional closing div for the column
				self::$col_open = false;
			}

			$row_html .= '</div>'; // end the previous row
		} else {
			$row_html .= '</ul>';
		}

		$row_html .= sprintf('<div class="row %s">', $field['cssClass']);

		self::$row_open = true;

		return $row_html;
	}

	static protected function process_column($form, $field)
	{
		// error_log(sprintf('process_column [row_open = %d | col_open = %d]', self::$row_open, self::$col_open));

		$col_html = '';

		if (!self::$row_open) {
			$col_html .= '<div class="row">';
			self::$row_open = true;
		}

		if (self::$col_open) {
			$col_html .= '</ul></div>';
		}

		$col_html .= sprintf('<div class="columns %s"><ul class="%s">', $field['cssClass'], GFCommon::get_ul_classes($form));

		self::$col_open = true;

		return $col_html;
	}
}
