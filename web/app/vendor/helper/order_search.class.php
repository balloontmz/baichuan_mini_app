<?php
/**
 * author      :  LiZhen
 * createTime  :  2019/12/2 9:44
 * description :  活动查询助手
 */

namespace app\vendor\helper;

use app\order\Order_Model;
use app\product\First_Product_Model;
use app\product\Product_Model;
use mysql_xdevapi\Exception;
use yangzie\YZE_DBAException;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_FatalException;
use yangzie\YZE_SQL;
use yangzie\YZE_Where;

class Order_Search extends Base_Search
{


    /**
     * 分页 页码
     * @var integer
     */
    public $page;

    /**
     * 分页 页面大小
     * @var integer
     */
    public $pagesize;

    /**
     * 状态
     * @var string
     */
    public $status;

    public function build_sql(YZE_SQL $sql, &$totalCount = 0)
    {
        try {
            $sql->from(Order_Model::CLASS_NAME, "o");
            if ($this->status)
                $sql->where("o", Order_Model::F_STATUS, YZE_SQL::EQ, $this->status);
            $sql->limit(($this->page - 1) * $this->pagesize, $this->pagesize);
            $activity_list = YZE_DBAImpl::getDBA()->select($sql);

            $sql->clean_groupby()->clean_select()->clean_limit();
            $sql->count("o", Order_Model::F_ID, "total", true);
            $obj = YZE_DBAImpl::getDBA()->getSingle($sql);
            $totalCount = $obj ? $obj->total : 0;
        } catch (Exception $e) {
            throw new YZE_FatalException($e->getMessage());
        }
        return $activity_list;
    }

    public function search(&$totalCount = 0)
    {
        return;
    }
}