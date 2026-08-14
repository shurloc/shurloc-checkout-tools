<?php
/**
 * WordPress function test doubles.
 *
 * @package ShurLocCheckoutTools
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

/**
 * Determines whether the current request is an admin request.
 */
function is_admin(): bool {
	return $GLOBALS['shurloc_test_is_admin'] ?? false;
}


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

	return in_array( $term, $terms, true );
}
