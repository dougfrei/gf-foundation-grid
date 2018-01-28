<?php
class GFFoundation
{
	protected $row_open = false;
	protected $col_open = false;


	public function __construct()
	{
		require_once('GFFoundationRow.php');
		require_once('GFFoundationColumn.php');

		GF_Fields::register(new GFFoundationRow());
		GF_Fields::register(new GFFoundationColumn());

		add_filter('gform_field_container', array(&$this, 'process_field_container'), 10, 6);
		add_filter('gform_get_form_filter', array(&$this, 'filter_form_output'), 10, 2);
	}

	public function process_field_container($field_container, $field, $form, $css_class, $style, $field_content)
	{
		if (is_admin()) {
			return $field_container;
		}

		$return_html = '';

		switch ($field['type']) {
			case 'foundation-row':
				$return_html = $this->process_row($field);
				break;

			case 'foundation-column':
				$return_html = $this->process_column($form, $field);
				break;

			default:
				$return_html = $field_container;
				break;
		}

		return $return_html;
	}

	public function filter_form_output($form_string, $form)
	{
		$closing_div_count = 0;

		if ($this->row_open) {
			$closing_div_count++;
		}

		if ($this->col_open) {
			$closing_div_count++;
		}

		$this->row_open = false;
		$this->col_open = false;

		return str_replace("<div class='gform_footer", str_repeat('</div>', $closing_div_count)."<div class='gform_footer", $form_string);
	}

	public function process_row($field)
	{
		$row_html = '';

		if ($this->row_open) {
			if ($this->col_open) {
				// add an additional closing div for the column
				$row_html .= '</ul></div>';

				$this->col_open = false;
			}

			// end the previous row
			$row_html .= '</div>';
		} else {
			$row_html .= '</ul>';
		}

		$row_html .= sprintf('<div class="row %s">', $field['cssClass']);

		$this->row_open = true;

		return $row_html;
	}

	public function process_column($form, $field)
	{
		$col_html = '';

		if (!$this->row_open) {
			$col_html .= '<div class="row">';

			$this->row_open = true;
		}

		if ($this->col_open) {
			$col_html .= '</ul></div>';
		}

		$col_html .= sprintf('<div class="columns %s"><ul class="%s">', $field['cssClass'], GFCommon::get_ul_classes($form));

		$this->col_open = true;

		return $col_html;
	}
}
