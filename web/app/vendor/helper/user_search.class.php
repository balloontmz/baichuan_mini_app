<?php

namespace app\vendor\helper;


use app\user\User_Model;
use mysql_xdevapi\Exception;
use yangzie\YZE_DBAException;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_FatalException;
use yangzie\YZE_SQL;
use yangzie\YZE_Where;

class User_Search extends Base_Search
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
     * wx_appid
     * @var string
     */
    public $wx_appid;

    /**
     * 用户名
     * @var string
     */
    public $user_name;

    /**
     * 禁用状态
     * @var string
     */
    public $status;

    public function build_sql(YZE_SQL $sql, &$totalCount = 0)
    {
        try {
            $sql->from(User_Model::CLASS_NAME, "u");
            $sql->order_by("u", User_Model::F_ID, "desc");
            $sql->where("u",User_Model::F_NAME,YZE_SQL::ISNOTNULL);
            $sql->where("u",User_Model::F_CELLPHONE,YZE_SQL::ISNOTNULL);
            if ($this->wx_appid)
                $sql->where("u", User_Model::F_WX_APPID, YZE_SQL::EQ, $this->wx_appid);
            if ($this->user_name)
                $sql->where("u", User_Model::F_NAME, YZE_SQL::LIKE, $this->user_name);
            if ($this->status)
                $sql->where("u", User_Model::F_STATUS, YZE_SQL::EQ, $this->status);

            $sql->limit(($this->page - 1) * $this->pagesize, $this->pagesize);
            $list = YZE_DBAImpl::getDBA()->select($sql);

            $sql->clean_groupby()->clean_select()->clean_limit();
            $sql->count("u", User_Model::F_ID, "total", true);
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