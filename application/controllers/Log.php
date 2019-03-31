<?php

class LogController extends Yaf_Controller_Abstract {

	/**
	 * LogController::init()
	 * @return void
	 */
	public function init() {
		# parent::init();
    }

	/**
	 * 显示权限操作日志页面
	 */
	public function privilegelogAction() {
		$params = array();
		$this->getView()->make('log.privilegelog',$params);
	}

    /**
     * 查询权限操作日志数据
     */
	public function privilegelogJsonAction() {
		$request = $this->getRequest();
		$search = array (
			'bar_controller' => $request->getPost('bar_controller',''),
			'bar_action' => $request->getPost('bar_action',''),
			'bar_realname' => $request->getPost('bar_realname',''),
			'pageCurrent' => COMMON :: P(),
			'pageSize' => COMMON :: PR(),
			'orders'  => $request->getPost('orders',''),

		);
		$L = new LogModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		$list = $L->searchPrivilegeLog($search);
		echo json_encode($list);
	}

    /**
     * 显示单据操作日志页面
     */
	public function doclogAction() {
		$params = array();
		$this->getView()->make('log.doclog',$params);
	}

    /**
     * 查询单据操作日志数据
     */
	public function doclogJsonAction() {
		$request = $this->getRequest();
		$id=$request->getPost('id',$request->getParam('id','0'));
		$search = array (
			'bar_id' => $id,
			'page' => false,
			'orders'  => $request->getPost('orders',''),
			'bar_doctype' => $request->getPost('doctype',''),
		);
		$L = new LogModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
		$list = $L->searchDocLog($search);
		echo json_encode($list);
	}

}
