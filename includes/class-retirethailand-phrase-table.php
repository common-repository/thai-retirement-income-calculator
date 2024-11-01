<?php

class Retirethailand_Phrase_Table extends WP_List_Table
{
		public function prepare_items()
		{
		    $columns = $this->get_columns();
		    $hidden = $this->get_hidden_columns();
		    $sortable = $this->get_sortable_columns();

		    $data = $this->table_data();
		    usort( $data, array( &$this, 'sort_data' ) );

		    $perPage = 20;
		    $currentPage = $this->get_pagenum();
		    $totalItems = count($data);

		    $this->set_pagination_args( array(
		        'total_items' => $totalItems,
		        'per_page'    => $perPage
		    ) );

		    $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

		    $this->_column_headers = array($columns, $hidden, $sortable);
		    $this->items = $data;
		}
		/**
		 * Override the parent columns method. Defines the columns to use in your listing table
		 *
		 * @return Array
		 */
		public function get_columns()
		{
		    $columns = array(
		        'stat_id'		=> 'Stat ID',
		        'stat_ip'		=> 'IP',
		        'cur_id'		=> 'Currency',
		        'cur_rate'		=> 'Rate',
		        'cur_monthly'	=> 'Monthly',
		        'stat_browser'	=> 'Browser',
		        'stat_referrer'	=> 'Referrer',
		        'stat_date'     => 'Date'
		    );

		    return $columns;
		}
		/**
		 * Define which columns are hidden
		 *
		 * @return Array
		 */
		public function get_hidden_columns()
		{
		    return array('stat_id');
		}
		/**
		 * Define the sortable columns
		 *
		 * @return Array
		 */
		public function get_sortable_columns()
		{
		    return array(
		    	'cur_id' => array('cur_id', false),
		    	'cur_rate' => array('cur_rate', false),
		    	'cur_monthly' => array('cur_monthly', false),
		    	'stat_date' => array('stat_date', false)		    	
		    );
		}
		
		/**
		 * Get the table data
		 *
		 * @return Array
		 */
		private function table_data()
		{
		    global $wpdb;
		    $data = array();

			$results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'retirethailand_requests');
			
			if($results){
				foreach($results as $row){

					if(isset($_REQUEST['active_front_filter']) && $_REQUEST['active_front_filter'] == 0) $active_front = 0;
			 		else $active_front = 1;

					$data[] = array(
						'stat_id'		=>  $row->stat_id,
						'stat_ip'		=>  $row->stat_ip,
						'cur_id'		=>  $row->cur_id,
						'cur_rate'		=>  $row->cur_rate,
						'cur_monthly'	=>	$row->cur_monthly,
						'stat_browser'	=>  $row->stat_browser,
						'stat_referrer'	=>  $row->stat_referrer,
						'stat_date'		=>  $row->stat_date
					);
				}
			}

		    return $data;
		}
		
		// Used to display the value of the id column
		public function column_id($item)
		{
			return $item['stat_id'];
		}
		
		/**
		 * Define what data to show on each column of the table
		 *
		 * @param  Array $item        Data
		 * @param  String $column_name - Current column name
		 *
		 * @return Mixed
		 */
		public function column_default( $item, $column_name )
		{
		    switch( $column_name ) {
		        case 'stat_id':
		        case 'stat_ip':
		        case 'cur_id':
		        case 'cur_rate':
		        case 'cur_monthly':
		        case 'stat_browser':
		        case 'stat_referrer':
		        case 'stat_date':
		            return $item[ $column_name ];

		        default:
		            return print_r( $item, true ) ;
		    }
		}

		/**
		 * Allows you to sort the data by the variables set in the $_GET
		 *
		 * @return Mixed
		 */
		private function sort_data( $a, $b )
		{
		    // Set defaults
		    $orderby = 'stat_date';
		    $order = 'desc';

		    // If orderby is set, use this as the sort column
		    if(!empty($_GET['orderby']))
		    {
		        $orderby = $_GET['orderby'];
		    }

		    // If order is set use this as the order
		    if(!empty($_GET['order']))
		    {
		        $order = $_GET['order'];
		    }

		    $result = strnatcmp( $a[$orderby], $b[$orderby] );

		    if($order === 'asc')
		    {
		        return $result;
		    }

		    return -$result;
		}
		
}
	

