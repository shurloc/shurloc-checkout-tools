<?php
/**
 * WordPress function test doubles.
 *
 * @package ShurlocCheckoutTools
 */

declare( strict_types=1 );

if ( ! function_exists( 'plugin_dir_path' ) ) {

	/**
	 * Get the filesystem directory path for a plugin file.
	 *
	 * Test replacement for plugin_dir_path().
	 *
	 * @param string $file Plugin file path.
	 * @return string
	 */
	function plugin_dir_path(
		string $file
	): string {

		return trailingslashit(
			dirname( $file )
		);
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {

	/**
	 * Get the URL for a plugin file's directory.
	 *
	 * Test replacement for plugin_dir_url().
	 *
	 * @param string $file Plugin file path.
	 * @return string
	 */
	function plugin_dir_url(
		string $file
	): string {

		unset( $file );

		return 'https://example.com/wp-content/plugins/shurloc-checkout-tools/';
	}
}

if ( ! function_exists( 'trailingslashit' ) ) {

	/**
	 * Add a trailing slash to a string.
	 *
	 * Test replacement for trailingslashit().
	 *
	 * @param string $value Value.
	 * @return string
	 */
	function trailingslashit(
		string $value
	): string {

		return rtrim(
			$value,
			'/\\'
		) . '/';
	}
}

if ( ! function_exists( 'add_action' ) ) {

	/**
	 * Registers a WordPress action.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Hook priority.
	 * @param int      $accepted_args Number of accepted arguments.
	 */
	function add_action(
		string $hook,
		callable $callback,
		int $priority = 10,
		int $accepted_args = 1
	): void {

		$GLOBALS['shurloc_test_actions'][] = array(
			'hook'          => $hook,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);
	}
}

if ( ! function_exists( 'is_admin' ) ) {

	/**
	 * Determines whether the current request is an admin request.
	 */
	function is_admin(): bool {

		return $GLOBALS['shurloc_test_is_admin'] ?? false;
	}
}

if ( ! function_exists( 'has_term' ) ) {

	/**
	 * Determines whether a post has a taxonomy term.
	 *
	 * @param int|string|array<int|string> $term     Term slug, ID, name, or terms.
	 * @param string                       $taxonomy Taxonomy name.
	 * @param int                          $post     Post ID.
	 */
	function has_term(
		int|string|array $term,
		string $taxonomy,
		int $post
	): bool {

		$terms = $GLOBALS['shurloc_test_terms'][ $post ][ $taxonomy ] ?? array();

		if ( is_array( $term ) ) {
			return array_intersect( $term, $terms ) !== array();
		}

		return in_array(
			$term,
			$terms,
			true
		);
	}
}

if ( ! function_exists( 'wp_enqueue_style' ) ) {

	/**
	 * Enqueues a stylesheet.
	 *
	 * @param string   $handle Stylesheet handle.
	 * @param string   $src    Stylesheet URL.
	 * @param string[] $deps   Dependencies.
	 * @param string   $ver    Version.
	 */
	function wp_enqueue_style(
		string $handle,
		string $src = '',
		array $deps = array(),
		string $ver = ''
	): void {

		$GLOBALS['shurloc_test_enqueued_styles'][] = array(
			'handle'       => $handle,
			'src'          => $src,
			'dependencies' => $deps,
			'version'      => $ver,
		);
	}
}

if ( ! function_exists( 'wp_enqueue_script' ) ) {

	/**
	 * Enqueues a script.
	 *
	 * @param string   $handle    Script handle.
	 * @param string   $src       Script URL.
	 * @param string[] $deps      Dependencies.
	 * @param string   $ver       Version.
	 * @param bool     $in_footer Whether to enqueue in the footer.
	 */
	function wp_enqueue_script(
		string $handle,
		string $src = '',
		array $deps = array(),
		string $ver = '',
		bool $in_footer = false
	): void {

		$GLOBALS['shurloc_test_enqueued_scripts'][] = array(
			'handle'       => $handle,
			'src'          => $src,
			'dependencies' => $deps,
			'version'      => $ver,
			'in_footer'    => $in_footer,
		);
	}
}

if ( ! function_exists( 'wp_localize_script' ) ) {

	/**
	 * Localizes data for a script.
	 *
	 * @param string               $handle      Script handle.
	 * @param string               $object_name JavaScript object name.
	 * @param array<string, mixed> $data        Data.
	 */
	function wp_localize_script(
		string $handle,
		string $object_name,
		array $data
	): bool {

		$GLOBALS['shurloc_test_localized_scripts'][] = array(
			'handle'      => $handle,
			'object_name' => $object_name,
			'data'        => $data,
		);

		return true;
	}
}

if ( ! function_exists( 'get_option' ) ) {

	/**
	 * Gets a WordPress option.
	 *
	 * @param string $option        Option name.
	 * @param mixed  $default_value Default value.
	 * @return mixed Option         value.
	 */
	function get_option(
		string $option,
		mixed $default_value = false
	): mixed {

		return $GLOBALS['shurloc_test_options'][ $option ] ?? $default_value;
	}
}

if ( ! function_exists( 'register_setting' ) ) {

	/**
	 * Registers a WordPress setting.
	 *
	 * @param string               $option_group Settings group.
	 * @param string               $option_name  Option name.
	 * @param array<string, mixed> $args         Registration arguments.
	 * @return void
	 */
	function register_setting(
		string $option_group,
		string $option_name,
		array $args = array()
	): void {
		$GLOBALS['shurloc_test_registered_settings'][] = array(
			'option_group' => $option_group,
			'option_name'  => $option_name,
			'args'         => $args,
		);
	}
}

if ( ! function_exists( 'add_settings_section' ) ) {

	/**
	 * Registers a settings section.
	 *
	 * @param string   $id       Section ID.
	 * @param string   $title    Section title.
	 * @param callable $callback Section callback.
	 * @param string   $page     Settings page.
	 * @return void
	 */
	function add_settings_section(
		string $id,
		string $title,
		callable $callback,
		string $page
	): void {
		$GLOBALS['shurloc_test_settings_sections'][] = array(
			'id'       => $id,
			'title'    => $title,
			'callback' => $callback,
			'page'     => $page,
		);
	}
}

if ( ! function_exists( 'add_settings_field' ) ) {

	/**
	 * Registers a settings field.
	 *
	 * @param string   $id       Field ID.
	 * @param string   $title    Field title.
	 * @param callable $callback Field callback.
	 * @param string   $page     Settings page.
	 * @param string   $section  Settings section.
	 * @return void
	 */
	function add_settings_field(
		string $id,
		string $title,
		callable $callback,
		string $page,
		string $section = 'default'
	): void {
		$GLOBALS['shurloc_test_settings_fields'][] = array(
			'id'       => $id,
			'title'    => $title,
			'callback' => $callback,
			'page'     => $page,
			'section'  => $section,
		);
	}
}

if ( ! function_exists( 'checked' ) ) {

	/**
	 * Outputs or returns the checked HTML attribute.
	 *
	 * @param mixed $checked Current value.
	 * @param mixed $current Value to compare against.
	 * @param bool  $display Whether to echo the attribute.
	 * @return string Checked attribute.
	 */
	function checked(
		mixed $checked,
		mixed $current = true,
		bool $display = true
	): string {
		$result = $checked === $current
			? ' checked="checked"'
			: '';

		if ( $display ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Test double matches WordPress checked() output behavior.
			echo $result;
		}

		return $result;
	}
}

if ( ! function_exists( 'esc_attr' ) ) {

	/**
	 * Escapes a value for use in an HTML attribute.
	 *
	 * @param string $text Text to escape.
	 * @return string Escaped text.
	 */
	function esc_attr(
		string $text
	): string {
		return htmlspecialchars(
			$text,
			ENT_QUOTES | ENT_SUBSTITUTE,
			'UTF-8'
		);
	}
}

if ( ! function_exists( 'esc_textarea' ) ) {

	/**
	 * Escapes text for use in a textarea.
	 *
	 * @param string $text Text to escape.
	 * @return string Escaped text.
	 */
	function esc_textarea(
		string $text
	): string {
		return htmlspecialchars(
			$text,
			ENT_QUOTES | ENT_SUBSTITUTE,
			'UTF-8'
		);
	}
}

if ( ! function_exists( 'sanitize_textarea_field' ) ) {

	/**
	 * Sanitizes a multiline text field.
	 *
	 * @param string $text Text to sanitize.
	 * @return string Sanitized text.
	 */
	function sanitize_textarea_field(
		string $text
	): string {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- Reduce dependency on WP functions
		$text = strip_tags( $text );

		$text = str_replace(
			array( "\r\n", "\r" ),
			"\n",
			$text
		);

		return trim( $text );
	}
}

if ( ! function_exists( 'current_user_can' ) ) {

	/**
	 * Determines whether the current user has a capability.
	 *
	 * @param string $capability Capability name.
	 * @return bool Whether the current user has the capability.
	 */
	function current_user_can(
		string $capability
	): bool {
		unset( $capability );

		return $GLOBALS['shurloc_test_current_user_can'] ?? false;
	}
}

if ( ! function_exists( 'add_submenu_page' ) ) {

	/**
	 * Registers an admin submenu page.
	 *
	 * @param string         $parent_slug Parent menu slug.
	 * @param string         $page_title  Page title.
	 * @param string         $menu_title  Menu title.
	 * @param string         $capability  Required capability.
	 * @param string         $menu_slug   Menu slug.
	 * @param callable|null  $callback    Page callback.
	 * @param int|float|null $position   Menu position.
	 * @return string Hook suffix.
	 */
	function add_submenu_page(
		string $parent_slug,
		string $page_title,
		string $menu_title,
		string $capability,
		string $menu_slug,
		callable|null $callback = null,
		int|float|null $position = null
	): string {
		$GLOBALS['shurloc_test_submenu_pages'][] = array(
			'parent_slug' => $parent_slug,
			'page_title'  => $page_title,
			'menu_title'  => $menu_title,
			'capability'  => $capability,
			'menu_slug'   => $menu_slug,
			'callback'    => $callback,
			'position'    => $position,
		);

		return 'shurloc-checkout-tools';
	}
}

if ( ! function_exists( 'esc_url' ) ) {

	/**
	 * Escapes a URL.
	 *
	 * @param string $url URL to escape.
	 * @return string Escaped URL.
	 */
	function esc_url(
		string $url
	): string {
		return $url;
	}
}

if ( ! function_exists( 'add_query_arg' ) ) {

	/**
	 * Adds query arguments to a URL.
	 *
	 * @param array<string, scalar> $args Query arguments.
	 * @param string                $url  URL.
	 * @return string URL with query arguments.
	 */
	function add_query_arg(
		array $args,
		string $url
	): string {
		$query = http_build_query( $args );

		if ( '' === $query ) {
			return $url;
		}

		$separator = str_contains( $url, '?' )
			? '&'
			: '?';

		return $url . $separator . $query;
	}
}

if ( ! function_exists( 'admin_url' ) ) {

	/**
	 * Gets a URL within the WordPress admin area.
	 *
	 * @param string $path Path relative to the admin directory.
	 * @return string Admin URL.
	 */
	function admin_url(
		string $path = ''
	): string {
		return 'https://example.com/wp-admin/' . ltrim( $path, '/' );
	}
}
