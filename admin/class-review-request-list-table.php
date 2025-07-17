<?php
/**
 * Table holds all the notify details.
 *
 * @package review-requester-for-woocommerce\admin\
 * @author Masood Mohamed <iam.masoodmohd@gmail.com>
 * @version 1.0
 */

namespace RRW;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Table holds all the notify details.
 */
class Review_Request_List_Table extends \WP_List_Table {

	private $data = [];
	private $total_items;

	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'notification',
				'plural'   => 'notifications',
				'ajax'     => false,
			)
		);
	}

	public function get_columns() {
		return array(
			'cb'         => '<input type="checkbox" />',
			'id'         => esc_html__( 'Order ID', 'review-requester-for-woocommerce' ),
			'email'      => esc_html__( 'Email', 'review-requester-for-woocommerce' ),
			'status'     => esc_html__( 'Status', 'review-requester-for-woocommerce' ),
			'created_at' => esc_html__( 'Created At', 'review-requester-for-woocommerce' ),
			'sent_at'    => esc_html__( 'Sent At', 'review-requester-for-woocommerce' ),
		);
	}

	public function get_sortable_columns() {
		return array(
			'id'         => array( 'id', true ),
			'email'      => array( 'email', false ),
			'status'     => array( 'status', false ),
			'created_at' => array( 'created_at', false ),
		);
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="notification[]" value="%s" />', $item['id'] );
	}

	public function column_id( $item ) {
		return isset( $item['order_id'] ) ? $item['order_id'] : '';
	}

	public function column_email( $item ) {
		return isset( $item['email'] ) ? $item['email'] : '';
	}

	public function column_status( $item ) {
		return $item['status'] ? $item['status'] : '-';
	}

	public function column_created_at( $item ) {
		return $item['created_at'];
	}

	public function column_sent_at( $item ) {
		return $item['sent_at'];
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array(
			'delete' => esc_html__( 'Delete permanently', 'review-requester-for-woocommerce' ),
		);
	}

	private function process_bulk_actions() {
		if ( 'delete' === $this->current_action() && ! empty( $_REQUEST['notification'] ) ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'rrw_review_requests';
			$ids        = array_map( 'absint', $_REQUEST['notification'] );

			if ( ! empty( $ids ) ) {
				$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );
				$query        = "DELETE FROM $table_name WHERE id IN ($placeholders)";

				$wpdb->query( $wpdb->prepare( $query, ...$ids ) );
			}
		}
	}

	public function prepare_items() {
		$this->process_bulk_actions();
		global $wpdb;

		$table_name   = $wpdb->prefix . 'rrw_review_requests';
		$per_page     = 10;
		$current_page = $this->get_pagenum();

		$offset = ( $current_page - 1 ) * $per_page;

		$orderby = ! empty( $_GET['orderby'] ) ? esc_sql( $_GET['orderby'] ) : 'id';
		$order   = ! empty( $_GET['order'] ) ? esc_sql( $_GET['order'] ) : 'DESC';

		$this->total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

		$this->data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
				$per_page,
				$offset
			),
			ARRAY_A
		);

		$this->items = $this->data;

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$this->set_pagination_args(
			array(
				'total_items' => $this->total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->total_items / $per_page ),
			)
		);
	}
}
