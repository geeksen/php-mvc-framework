<?php

class board extends controller
{
	var $files = array
	(
		'file1',
		'file2',
	);

	function index()
	{
		$request = $this->input_get(array
		(
			'page' => 1,
			'field' => 0,
			'keyword' => '',
			'per_page' => 3,
			'link_count' => 3,
		));

		$db = $this->load_database('pmf');

		$board_model = $this->load_model($db, $request, 'dir/board_model');
		$board_count = $board_model->select_count();
		$board_list = $board_model->select_list();

		$response = array
		(
			'h2_title' => 'Board :: Index',

			'field_columns' => $board_model->field_columns,
			'field_names' => $board_model->field_names,
			'page' => $request['page'],
			'field' => $request['field'],
			'keyword' => $request['keyword'],
			'board_count' => $board_count,
			'board_list' => $board_list,
				//'per_page' => $request['per_page'],
			'page_count' => (intval(($board_count - 1) / $request['per_page']) + 1),
			'link_count' => $request['link_count'],
		);

		$this->load_view('header', $response);
		$this->load_view('dir/board/index', $response);
		$this->load_view('footer', $response);
	}

	function show()
	{
		$request = $this->input_get(array
		(
			'page' => 1,
			'seq' => 1,
		));

		if (0 == $request['seq'])
		{
			error_handler(1, 'seq required');
		}

		$db = $this->load_database('pmf');

		$board_model = $this->load_model($db, $request, 'dir/board_model');
		$board_info = $board_model->select_info();

		$response = array
		(
			'h2_title' => 'Board :: Show',
			'page' => $request['page'],

			'seq' => $board_info->seq,
			'title' => $board_info->title,
			'content' => str_replace(array("\r\n", "\n", "\r"), "<br>\n", $board_info->content),
			'file1' => $board_info->file1,
			'file2' => $board_info->file2,
			'userid' => $board_info->userid,
			'inserttime' => $board_info->inserttime,
			'updatetime' => $board_info->updatetime,
		);

		$this->load_view('header', $response);
		$this->load_view('dir/board/show', $response);
		$this->load_view('footer', $response);
	}

	function form()
	{
		$request = $this->input_get(array
		(
			'page' => 1,
			'seq' => 0,
		));

		$response = array
		(
			'h2_title' => 'Board :: Form',
			'page' => $request['page'],

			'seq' => 0,
			'title' => '',
			'content' => '',
			'file1' => '',
			'file2' => '',
			'userid' => '',
			'inserttime' => '',
			'updatetime' => '',
		);

		if (0 != $request['seq'])
		{
			$db = $this->load_database('pmf');

			$board_model = $this->load_model($db, $request, 'dir/board_model');
			$board_info = $board_model->select_info();

			$response = array_merge($response, array
			(
				'seq' => $board_info->seq,
				'title' => $board_info->title,
				'content' => $board_info->content,
				'file1' => $board_info->file1,
				'file2' => $board_info->file2,
				'userid' => $board_info->userid,
				'inserttime' => $board_info->inserttime,
				'updatetime' => $board_info->updatetime,
			));
		}

		$this->load_view('header', $response);
		$this->load_view('dir/board/form', $response);
		$this->load_view('footer', $response);
	}

	function delete()
	{
		$request = $this->input_get(array
		(
			'page' => 1,
			'seq' => 0,
		));

		if (0 == $request['seq'])
		{
			error_handler(1, 'seq required');
		}

		$db = $this->load_database('pmf');

		$board_model = $this->load_model($db, $request, 'dir/board_model');
		$board_info = $board_model->select_info();

		$response = array
		(
			'h2_title' => 'Board :: Show',
			'page' => $request['page'],

			'seq' => $board_info->seq,
			'title' => $board_info->title,
			'content' => $board_info->content,
			'file1' => $board_info->file1,
			'file2' => $board_info->file2,
			'userid' => $board_info->userid,
			'inserttime' => $board_info->inserttime,
			'updatetime' => $board_info->updatetime,
		);

		$path = $this->upload_path . '/' . substr($response['inserttime'], 0, 7) . '/';
		foreach ($this->files as $file)
		{
			if ('' != $response[$file])
			{
				$filepath = $path . $response[$file];

				if (false === file_exists($filepath))
				{
					error_handler(1, 'file not found');
				}

				if (false === unlink($filepath))
				{
					error_handler(1, 'unlink failed');
				}
			}
		}

		$affected_rows = $board_model->delete();

		if (0 == $affected_rows)
		{
			error_handler(1, 'no affected_rows');
		}

		$this->redirect_to('/dir/board/index');
	}

	function post()
	{
		$request = $this->input_post(array
		(
			'page' => 1,
			'seq' => 0,
			'title' => '',
			'content' => '',
			'file1' => '',
			'file2' => '',
			'userid' => '',
		));

		$response = array
		(
			'page' => $request['page'],

			'seq' => 0,
			'title' => '',
			'content' => '',
			'file1' => '',
			'file2' => '',
			'userid' => '',
			'inserttime' => date('Y-m-d'),
			'updatetime' => '',
		);

		$db = $this->load_database('pmf');
		$board_model = $this->load_model($db, $request, 'dir/board_model');

		if (0 != $request['seq'])
		{
			$board_info = $board_model->select_info();

			$response = array_merge($response, array
			(
				'seq' => $board_info->seq,
				'title' => $board_info->title,
				'content' => $board_info->content,
				'file1' => $board_info->file1,
				'file2' => $board_info->file2,
				'userid' => $board_info->userid,
				'inserttime' => $board_info->inserttime,
				'updatetime' => $board_info->updatetime,
			));
		}

		$path = $this->upload_path . '/' . substr($response['inserttime'], 0, 7) . '/';
		$upload = $this->input_upload($path, $this->files);
		$request = array_merge($request, array
		(
			'file1' => $upload['file1'],
			'file2' => $upload['file2'],
		));
		$board_model->escape_request($request);

		if (0 != $request['seq'])
		{
			foreach ($this->files as $file)
			{
				if ('' == $request[$file] && '' != $response[$file])
				{
					$request[$file] = $response[$file];
				}
				else if ('' != $request[$file] && '' != $response[$file])
				{
					$filepath = $path . $response[$file];

					if (false === file_exists($filepath))
					{
						error_handler(1, 'file not found');
					}

					if (false === unlink($filepath))
					{
						error_handler(1, 'unlink failed');
					}
				}
			}
		}

		$affected_rows = 0;
		if (0 != $request['seq'])
		{
			$affected_rows = $board_model->update();
		}
		else
		{
			$affected_rows = $board_model->insert();
		}

		if (0 == $affected_rows)
		{
			error_handler(1, 'no affected_rows');
		}

		$this->redirect_to('/dir/board/index');
	}

	function batch()
	{
	
		$request = $this->input_post(array
		(
			'seqs' => array(),
			'delete_multiple' => '',
		));
		$request['seqs'] = array_map('intval', $request['seqs']);

		if (0 == count($request['seqs']))
		{
			error_handler(1, 'seqs required');
		}

		$db = $this->load_database('pmf');
		$board_model = $this->load_model($db, $request, 'dir/board_model');

		if ('' != $request['delete_multiple'])
		{
			$affected_rows = $board_model->delete_multiple();

			if (0 == $affected_rows)
			{
				error_handler(1, 'no affected_rows');
			}
		}

		$this->redirect_to('/dir/board/index');
	}
}