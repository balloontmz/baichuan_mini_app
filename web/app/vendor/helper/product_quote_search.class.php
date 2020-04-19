<?php


namespace app\vendor\helper;

use app\product\First_Product_Model;
use app\product_quote\Product_Price_Model;
use mysql_xdevapi\Exception;
use yangzie\YZE_DBAException;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_FatalException;
use yangzie\YZE_SQL;
use yangzie\YZE_Where;

class Product_Quote_Search extends Base_Search
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
     * 活动所属的团队
     * @var string
     */
    public $product_id;

    public function build_sql(YZE_SQL $sql, &$totalCount = 0)
    {
        try {
            $sql->from(Product_Price_Model::CLASS_NAME, "pq");
            $sql->order_by("pq",Product_Price_Model::F_ID,"desc");
            if ($this->product_id)
                $sql->where("pq", Product_Price_Model::F_PRODUCT_ID, YZE_SQL::EQ, $this->product_id);
            $sql->limit(($this->page - 1) * $this->pagesize, $this->pagesize);
            $list = YZE_DBAImpl::getDBA()->select($sql);

            $sql->clean_groupby()->clean_select()->clean_limit();
            $sql->count("pq", First_Product_Model::F_ID, "total", true);
            $obj = YZE_DBAImpl::getDBA()->getSingle($sql);
            $totalCount = $obj ? $obj->total : 0;
        } catch (Exception $e) {
            throw new YZE_FatalException($e->getMessage());
        }
        return $list;
    }

    public function search(&$totalCount = 0)
    {
        return;
    }
}