<?php
/**
 * Created by PhpStorm.
 * User: iy2
 * Date: 4/8/2016
 * Time: 2:42 PM
 */
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->select(implode(',', $this->column));
		$this->db->from($this->table);
		foreach ($this->table_join as $a => $x) {
			$this->db->join($a, $x[0], $x[1]);
		}
		$this->db->where($this->where);
		if ($this->group != '') {
			$this->db->group_by($this->group);
		}
	}

	private function _get_datatables_query_filter()
	{
		$this->_get_datatables_query();
		$i = 0;
		foreach ($this->column_search as $item) // loop column
		{
			if (isset($_POST['search']['value'])) // if datatable send POST for search
			{
				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}


	function get_datatables()
	{
		$this->_get_datatables_query_filter();
		$length = $this->input->post('length');
		$start = $this->input->post('start');
		if ($length != -1) {
			$this->db->limit($length, $start);
		}
		$query = $this->db->get();
		return $query;
	}

	function count_filtered()
	{
		$this->_get_datatables_query_filter();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where($this->primary, $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where($this->primary, $id);
		$this->db->delete($this->table);
	}

	/**
	 * @param $class
	 * @param $cari
	 * @param $order
	 */

	/**
	 * SEARH LIMIT JUMLAH MANUAL
	 */
	private function get_filter($class, $cari, $order, $order_by = array())
	{
		$class;
		$i = 0;
		foreach ($cari as $item) // loop column
		{
			if (isset($_POST['search']['value'])) // if datatable send POST for search
			{
				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($cari) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($order_by)) {
			$order = $order_by;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}


	function get_data($class, $cari, $order, $order_by = array())
	{
		$this->get_filter($class, $cari, $order, $order_by);
		$length = isset($_POST['length']) ? $_POST['length'] : '10';
		$start = isset($_POST['start']) ? $_POST['start'] : '';
		if ($length != -1) {
			$this->db->limit($length, $start);
		}
		$query = $this->db->get();
		return $query;
	}

	function get_jumlah_filter($class, $cari, $order)
	{
		$this->get_filter($class, $cari, $order);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_jumlah($class)
	{
		// $this->_get_datatables_query();
		$class;
		$query = $this->db->get();
		return $query->num_rows();
	}
}

?>
