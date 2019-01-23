# Cherry X Customizer module

Module for standard WordPress customizer. Allows to:

* Define all data as single array
* Manage fonts
* Get options from customizer in easier way than default (is instance saved for later usage)

## How to use:

1. Copy this module into your theme/plugin
2. Add path to `cherry-x-customizer.php` file to `CX_Loader` initialization.
3. Initialize module on `after_setup_theme` hook with priority `0` or later, Example:

```php
add_action( 'after_setup_theme', 'twentyseventeen_init', 0 );

function twentyseventeen_init() {

	new CX_Customizer( array(
		'prefix'     => 'twentyseventeen',
		'path'       => get_theme_file_path( 'framework/modules/customizer/' ),
		'capability' => 'edit_theme_options',
		'type'       => 'theme_mod',
		'options'    => array(
			'show_tagline' => array(
				'title'    => esc_html__( 'Show tagline after logo', 'twentyseventeen' ),
				'section'  => 'title_tagline',
				'priority' => 60,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'general_settings' => array(
				'title'       => esc_html__( 'General Site settings', 'twentyseventeen' ),
				'priority'    => 40,
				'type'        => 'panel',
			),
			'logo_favicon' => array(
				'title'       => esc_html__( 'Logo &amp; Favicon', 'twentyseventeen' ),
				'priority'    => 25,
				'panel'       => 'general_settings',
				'type'        => 'section',
			),
			'header_logo_type' => array(
				'title'   => esc_html__( 'Logo Type', 'twentyseventeen' ),
				'section' => 'logo_favicon',
				'default' => 'text',
				'field'   => 'radio',
				'choices' => array(
					'image' => esc_html__( 'Image', 'twentyseventeen' ),
					'text'  => esc_html__( 'Text', 'twentyseventeen' ),
				),
				'type' => 'control',
			),
		)
	) );

}
```

## Arguments:
`CX_Customizer` accepts an array of options with next structure:
* `prefix`        - theme mod / option prefix
* `path`          - path to module file
* `capability`    - user capability to restrict access for different user groups
* `type`          - options type for database - theme_mod or options
* `fonts_manager` - `CX_Fonts_Manager` instance to enqueue fonts (just pass a new empty instance - `'fonts_manager' => new CX_Fonts_Manager()` )
* `options`       - registered options array

`options` is an associative array, key - is an option key for database. Each option in array must contain `type` property - control, section or panel (accordingly standard customizer methods). 
Other properties are the same as for standard Customizer properties.
