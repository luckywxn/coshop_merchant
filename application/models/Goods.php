<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/3/12
 * Time: 16:06
 */

class GoodsModel
{
    /**
     * 数据库类实例
     * @var object
     */
    public $dbh = null;
    public $mch = null;

    /**
     * Constructor
     * @param   object $dbh
     * @return  void
     */
    public function __construct($dbh, $mch = null)
    {
        $this->dbh = $dbh;
        $this->mch = $mch;
    }

    /**
     * 查询商品列表
     */
    public function searchGoods($params)
    {
        $filter = array();
        if (isset($params['merchant_no']) && $params['merchant_no'] != '') {
            $filter[] = " g.`merchant_no` = '{$params['merchant_no']}' ";
        }
        if (isset($params['goodsname']) && $params['goodsname'] != '') {
            $filter[] = " g.`goodsname` LIKE '%".$params['goodsname']."%' ";
        }
        if (isset($params['goodsno']) && $params['goodsno'] != '') {
            $filter[] = " g.`goodsno` LIKE '%".$params['goodsno']."%' ";
        }
        if (isset($params['status']) && $params['status'] != '') {
            $filter[] = " g.`status` = {$params['status']} ";
        }
        $where =" WHERE g.`ok_del` = 0 ";
        if (1 <= count($filter)) {
            $where .= "AND ". implode(' AND ', $filter);
        }

        $result = $params;
        $sql = "SELECT COUNT(*)  from goods g $where ";
        $result['totalRow'] = $this->dbh->select_one($sql);
        $result['list'] = array();
        if ($result['totalRow'])
        {
            if( isset($params['page'] ) && $params['page'] == false){
                $sql = "select g.*,gc.classifyname from goods g
                        left join goods_classify gc on gc.sysno = g.classify_sysno 
                        $where ";
                $arr = 	$this->dbh->select($sql);
                $result['list'] = $arr;
            }else{
                $result['totalPage'] =  ceil($result['totalRow'] / $params['pageSize']);
                $this->dbh->set_page_num($params['pageCurrent'] );
                $this->dbh->set_page_rows($params['pageSize'] );
                $sql = "select g.*,gc.classifyname from goods g
                        left join goods_classify gc on gc.sysno = g.classify_sysno 
                        $where ";
                $arr = 	$this->dbh->select_page($sql);
                $result['list'] = $arr;
            }
        }

        return $result;
    }

    /**
     * 根据商品id查询商品信息
     */
    public function getGoodBySysno($sysno){
        $sql = "SELECT * FROM goods WHERE sysno = '$sysno'";
        return $this->dbh->select_row($sql);
    }

    /**
     * 查询商品分类
     */
    public function getGoodsClassify(){
        $sql = "SELECT * FROM goods_classify WHERE ok_del = false ";
        return $this->dbh->select($sql);
    }

    /**
     * 添加商品
     */
    public function addGood($data){
        $this->dbh->begin();
        try{
            $res = $this->dbh->insert('goods', $data);
            if (!$res) {
                $this->dbh->rollback();
                return false;
            }

            $this->dbh->commit();
            return $res;
        } catch (Exception $e) {
            $this->dbh->rollback();
            return false;
        }
    }

    /**
     * 更新商品
     */
    public function updateGood($sysno,$data){
        $this->dbh->begin();
        try {
            $res = $this->dbh->update('goods', $data, 'sysno=' . intval($sysno));
            if (!$res) {
                $this->dbh->rollback();
                return false;
            }

            $this->dbh->commit();
            return true;
        } catch (Exception $e) {
            $this->dbh->rollback();
            return false;
        }
    }


}