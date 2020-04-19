<?php

namespace app\vendor\helper;


use app\user\Store_User_Model;
use mysql_xdevapi\Exception;
use yangzie\YZE_DBAException;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_FatalException;
use yangzie\YZE_SQL;
use yangzie\YZE_Where;

class Store_User_Search extends Base_Search
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
    public $store_name;

    public function build_sql(YZE_SQL $sql, &$totalCount = 0)
    {
        try {
            $sql->from(Store_User_Model::CLASS_NAME, "su");
            $sql->order_by("su", Store_User_Model::F_ID, "desc");
            if ($this->store_name)
                $sql->where("su", Store_User_Model::F_NAME, YZE_SQL::LIKE, $this->store_name);
            $sql->limit(($this->page - 1) * $this->pagesize, $this->pagesize);
            $list = YZE_DBAImpl::getDBA()->select($sql);

            $sql->clean_groupby()->clean_select()->clean_limit();
            $sql->count("su", Store_User_Model::F_ID, "total", true);
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