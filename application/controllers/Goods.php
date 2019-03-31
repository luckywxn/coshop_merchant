<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/3/12
 * Time: 16:04
 */

class GoodsController extends Yaf_Controller_Abstract
{

    /**
     * GoodsController::init()
     * @return void
     */
    public function init() {
        # parent::init();
    }

    /**
     * 显示商品页面
     * @return string
     */
    public function listAction() {
        $params =  array();
        $this->getView()->make('goods.list',$params);
    }

    /**
     * 查询商品数据
     */
    public function goodsListJsonAction(){
        $request = $this->getRequest();
        $user  = Yaf_Registry::get(SSN_VAR);
        $search = array(
            'pageSize' => COMMON::PR(),
            'pageCurrent' => COMMON::P(),
            'merchant_no' =>$user['merchant_no'],
            'goodsname' => $request->getPost('goodsname',''),
            'goodsno' => $request->getPost('goodsno',''),
            'status' => $request->getPost('status','')
        );
        $G = new GoodsModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $params =  $G->searchGoods($search);
        echo json_encode($params);
    }

    /**
     * 显示编辑商品页面
     */
    public function goodsEditAction(){
        $params =  array();
        $request = $this->getRequest();
        $sysno = $request->getParam("sysno",'');
        $G = new GoodsModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $params['goodsClassify'] = $G ->getGoodsClassify();
        if($sysno){
            $params['action'] = "/goods/goodsEditJson";
            $params['good'] = $G->getGoodBySysno($sysno);
        }else{
            $params['action'] = "/goods/goodsNewJson";
        }
        $this->getView()->make('goods.edit',$params);
    }

    /**
     *新增商品
     */
    public function goodsNewJsonAction(){
        $request = $this->getRequest();
        $user  = Yaf_Registry::get(SSN_VAR);
        $G = new GoodsModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $data = array(
            'merchant_no'=>$user['merchant_no'],
            'classify_sysno'=>$request->getPost("classify_sysno",""),
            'goodsno'=>$request->getPost("goodsno",""),
            'goodsname'=>$request->getPost("goodsname",""),
            'price'=>$request->getPost("price",""),
            'introduce'=>$request->getPost("introduce",""),
            'status'=>$request->getPost("status",""),
            'created_at'=>"=NOW()"
        );
        if($res = $G->addGood($data)){
            $row = $G->getGoodBySysno($res);
            COMMON::result(200,'更新成功',$row);
        }else{
            COMMON::result(300,'更新失败');
        }
    }

    /**
     * 编辑商品
     */
    public function goodsEditJsonAction(){
        $request = $this->getRequest();
        $sysno = $request ->getPost("sysno",'');
        $G = new GoodsModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $data = array(
            'classify_sysno'=>$request->getPost("classify_sysno",""),
            'goodsno'=>$request->getPost("goodsno",""),
            'goodsname'=>$request->getPost("goodsname",""),
            'price'=>$request->getPost("price",""),
            'introduce'=>$request->getPost("introduce",""),
            'status'=>$request->getPost("status",""),
            'updated_at'=>"=NOW()"
        );
        if($G->updateGood($sysno,$data)){
            $row = $G->getGoodBySysno($sysno);
            COMMON::result(200,'更新成功',$row);
        }else{
            COMMON::result(300,'更新失败');
        }
    }

    /**
     * 删除商品
     */
    public function goodsdeljsonAction(){
        $request = $this->getRequest();
        $sysno = $request ->getParam("sysno",'');
        $G = new GoodsModel(Yaf_Registry :: get("db"), Yaf_Registry :: get('mc'));
        $data = array(
            'ok_del'=>true,
            'updated_at'=>"=NOW()"
        );
        if($G->updateGood($sysno,$data)){
            $row = $G->getGoodBySysno($sysno);
            COMMON::result(200,'更新成功',$row);
        }else{
            COMMON::result(300,'更新失败');
        }
    }


}