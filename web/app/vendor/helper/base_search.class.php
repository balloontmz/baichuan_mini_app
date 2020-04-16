<?php
namespace app\vendor\helper;
use yangzie\YZE_SQL;

/**
 * 查询助手基类
 *
 * @author ydhlleeboo
 */
abstract class Base_Search {
    /**
     * 每页查询多少条数据, 会根据 page自动计算limit, 但如果指定了limit 参数,该参数和page无效
     * @var int 
     */
    public $pageSize = 10;
    /**
     * 查询第几页的数据, 会根据 pageSize自动计算limit, 但如果指定了limit 参数,该参数和pageSize无效
     * 第一页从1开始
     * @var int 
     */
    public $page;
    /**
     * sql中的分页,传入数组,比如 [10, 20] 表示从第10条开始,查询出20条出来, 也可以指定一个数字, 比如1 就表示只查询满足结果的第一条
     * 如果指定了该参数,pageSize和page无效
     * @var array|int
     */
    public $limit = [];
    /**
     * 之类需要继承并在传入的SQL上实现自己的sql构造方法
     * 传入的sql方法以及处理好了分页,之类只需要关注自己的查询逻辑即可
     */
    public abstract function build_sql(YZE_SQL $sql, &$totalCount=0);
    
    /**
     * 查询接口, 总数通过totalCount返回
     * 查询结果自行处理返回对象数组
     */
    public function search( &$totalCount = 0){
        $sql = new YZE_SQL();
        if ($this->limit){
            if(is_array($this->limit)){
                $sql->limit($this->limit[0], $this->limit[1]);
            }else{
                $sql->limit($this->limit);
            }
        }else{
            if( $this->page<1 ) $this->page = 1;
            $this->pageSize = $this->pageSize?:10;
            
            $sql->limit(($this->page - 1 )* $this->pageSize, $this->pageSize);
        }
        return $this->build_sql($sql, $totalCount);
    }
}
