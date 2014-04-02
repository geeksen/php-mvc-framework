<?php

class msg extends controller
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

		$db = $this->load_database('msgdb');

		$msg_model = $this->load_model($db, $request, 'dir/msg_model');
		$msg_list = $msg_model->select_list();

		$response = array
		(
			'msg_list' => $msg_list,
		);

		$this->load_view('dir/msg/index', $response);
	}

	function view()
	{
		$request = $this->input_get(array
		(
			'msgseq' => 1,
		));

		if (0 == $request['msgseq'])
		{
			error_handler('msgseq is required');
		}

		$db = $this->load_database('msgdb');

		$msg_model = $this->load_model($db, $request, 'dir/msg_model');
		$msg_info = $msg_model->select_info();

		$response = array
		(
			'msgseq' => $msg_info->msgseq,
			'msgtext' => $msg_info->msgtext,
		);

		$this->load_view('dir/msg/view', $response);
	}

	function form()
	{
		$request = $this->input_get(array
		(
			'msgseq' => 0,
		));

		$response = array
		(
			'msgseq' => 0,
			'msgtext' => '',
		);

		if (0 < $request['msgseq'])
		{
			$db = $this->load_database('msgdb');

			$msg_model = $this->load_model($db, $request, 'dir/msg_model');
			$msg_info = $msg_model->select_info();

			$response = array_merge(array
			(
				'msgseq' => $msg_info->msgseq,
				'msgtext' => $msg_info->msgtext,
			));
		}

		$this->load_view('dir/msg/form', $response);
	}

	function exec_get()
	{
		$request = $this->input_get(array
		(
			'msgseq' => 0,
		));

		$db = $this->load_database('msgdb');

		$msg_model = $this->load_model($db, $request, 'dir/msg_model');
		$affected_rows = $msg_model->delete();

		if (0 == $affected_rows)
		{
			error_handler('failed to delete');
		}

		$this->redirect_to('/dir/msg/index');
	}

	function exec_post()
	{
		$request = $this->input_post(array
		(
			'msgseq' => 0,
			'msgtext' => '',
		));

		$db = $this->load_database('msgdb');
		$msg_model = $this->load_model($db, $request, 'dir/msg_model');

		$affected_rows = 0;
		if (0 == $request['msgseq'])
		{
			$affected_rows = $msg_model->insert();
		}
		else
		{
			$affected_rows = $msg_model->update();
		}

		if (0 == $affected_rows)
		{
			error_handler('failed to exec_post');
		}

		$this->redirect_to('/dir/msg/index');
	}
}
