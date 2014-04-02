<?php

class board extends controller
{
	function index()
	{
		$request = $this->input_get(array
		(
			'page' => 1,
			'field' => 0,
			'keyword' => '',
			'per_page' => 10,
		));

		$db = $this->load_database('pmf');

		$board_model = $this->load_model($db, $request, 'dir/board_model');
		$board_list = $board_model->select_list();

		$response = array
		(
			'title' => 'Board :: Index',
			'field_columns' => $board_model->field_columns,
			'field_names' => $board_model->field_names,
			'page' => $request['page'],
			'field' => $request['field'],
			'keyword' => $request['keyword'],
			'board_list' => $board_list,
		);

		$this->load_view('header', $response);
		$this->load_view('dir/board/index', $response);
		$this->load_view('footer', $response);
	}

	function view()
	{
		$request = $this->input_get(array
		(
			'seq' => 1,
		));

		if (0 == $request['seq'])
		{
			error_handler('seq is required');
		}

		$db = $this->load_database('pmf');

		$board_model = $this->load_model($db, $request, 'dir/board_model');
		$board_info = $board_model->select_info();

		$response = array
		(
			'seq' => $board_info->seq,
			'title' => $board_info->title,
			'content' => $board_info->content,
			'userid' => $board_info->userid,
			'inserttime' => $board_info->inserttime,
			'updatetime' => $board_info->updatetime,
		);

		$this->load_view('header', $response);
		$this->load_view('dir/board/view', $response);
		$this->load_view('footer', $response);
	}

	function form()
	{
		$request = $this->input_get(array
		(
			'seq' => 0,
		));

		$response = array
		(
			'seq' => 0,
			'title' => '',
			'content' => '',
			'userid' => '',
			'inserttime' => '',
			'updatetime' => '',
		);

		if (0 < $request['seq'])
		{
			$db = $this->load_database('pmf');

			$board_model = $this->load_model($db, $request, 'dir/board_model');
			$board_info = $board_model->select_info();

			$response = array_merge(array
			(
				'seq' => $board_info->seq,
				'title' => $board_info->title,
				'content' => $board_info->content,
				'userid' => $board_info->userid,
				'inserttime' => $board_info->inserttime,
				'updatetime' => $board_info->updatetime,
			));
		}

		$this->load_view('header', $response);
		$this->load_view('dir/board/form', $response);
		$this->load_view('footer', $response);
	}

	function exec_get()
	{
		$request = $this->input_get(array
		(
			'seq' => 0,
		));

		$db = $this->load_database('pmf');

		$board_model = $this->load_model($db, $request, 'dir/board_model');
		$affected_rows = $board_model->delete();

		if (0 == $affected_rows)
		{
			error_handler('failed to exec_get');
		}

		$this->redirect_to('/dir/board/index');
	}

	function exec_post()
	{
		$request = $this->input_post(array
		(
			'seq' => 0,
		));

		$db = $this->load_database('pmf');
		$board_model = $this->load_model($db, $request, 'dir/board_model');

		$affected_rows = 0;
		if (0 == $request['seq'])
		{
			$affected_rows = $board_model->insert();
		}
		else
		{
			$affected_rows = $board_model->update();
		}

		if (0 == $affected_rows)
		{
			error_handler('failed to exec_post');
		}

		$this->redirect_to('/dir/board/index');
	}

	function exec_batch()
	{
	}
}
