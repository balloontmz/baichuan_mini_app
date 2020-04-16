<?php
/**
 * author      :  LiZhen
 * createTime  :  2019/12/2 9:44
 * description :  活动查询助手
 */

namespace app\vendor\helper;

use app\attribute\Second_Attribute_Model;
use app\product\Product_Model;
use mysql_xdevapi\Exception;
use yangzie\YZE_DBAException;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_FatalException;
use yangzie\YZE_SQL;
use yangzie\YZE_Where;

class Second_Attribute_Search extends Base_Search
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
    public $second_attribute_name;

    public function build_sql(YZE_SQL $sql, &$totalCount = 0)
    {
        try {
            $sql->from(Second_Attribute_Model::CLASS_NAME, "s");
            if ($this->second_attribute_name)
                $sql->where("s", Second_Attribute_Model::F_NAME, YZE_SQL::EQ, $this->second_attribute_name);
            $sql->limit(($this->page - 1) * $this->pagesize, $this->pagesize);
            $list = YZE_DBAImpl::getDBA()->select($sql);

            $sql->clean_groupby()->clean_select()->clean_limit();
            $sql->count("s", Second_Attribute_Model::F_ID, "total", true);
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