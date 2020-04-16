<?php
namespace app\vendor;

use app\asset\Asset_Model;
use app\cms\Notify_Model;
use yangzie\YZE_FatalException;
use yangzie\YZE_Hook;
use yangzie\YZE_Model;
use yangzie\YZE_Request;

/**
 * 自动保存Model助手，根据设置自动新增或者保存数据，及处理验证
 * 主要目标是自动把表单提交上来的数据设置给对应的model对象并进行保存操作；支持同时处理多个model
 * 所以强制要求就是表单数据的名称必须是"字段名称"或者"前缀-字段名称"
 *
 * 前缀不能是数字, 同一个model所有的前缀必须对应
 *
 * 单表处理可以不用别名，多表处理必须都有别名
 *
 * 提交上来的数据中，id uuid等自动生成的字段会被忽略
 *
 * @package app\vendor
 */
class Save_Model_Helper
{
    /**
     * 指定的字段不能为空
     */
    const RULE_NOT_EMPTY = "not_empty";
    /**
     * 字段的值在数据库中is_deleted=0中必须唯一
     */
    const RULE_UNIQUE    = "unique";
    /**
     * 需要处理的class及其对应的别名，这些别名也是表单提交上来的数据的字段前缀，比如：
     * ["a"=>User_Model::CLASS_NAME,]
     *
     * 如果只有一个Model处理，可以不用指定别名，比如[User_Model::CLASS_NAME]或者 User_Model::CLASS_NAME
     * @var array
     */
    public $alias_classes =[];
    /**
     * @var array 对提交上来的字段的验证规则，格式如下：
     * ["别名"=>["字段名"=>["规则"=>"错误提示信息"]]],也可以指定自定义的验证函数：
     * ["别名"=>["字段名"=>function($model, $value){}]], $value就是需要验证的值，如果验证失败，函数中抛出异常即可
     *
     * 如果只有一个model，格式如下：
     * ["字段名"=>["规则"=>"错误提示信息"]],也可以指定自定义的验证函数：
     * ["字段名"=>function($model, $value){}], $value就是需要验证的值，如果验证失败，函数中抛出异常即可;
     * 也可以再回调函数中重新设置值，这和$fetch_modify_model的作用一样，但要注意回调函数的model必须加上&
     *
     * 如果一个字段需要多个规则，可以传入一个数组
     * $model 是把所有数据设置好后的model
     *
     */
    public $valid_column_and_rules   =[];
    /**
     * @var array 对于alias_class中的model，那些是需要在保存数据库前根据知道的字段先从数据库中查询出来的，主要用于修改的情况，需要把修改的model先fetch出来，格式如下：
     * ["别名"=>function(){}],
     *
     * 如果只有一个model类型，格式如下：
     * function(){},
     *
     * 回调函数返回对应的model，如果是同一个model的多个实例，则返回mdoel数组，key需要和post提交的参数字段的key对应
     * 并且要设置multi_model_identity_column属性
     */
    public $fetch_modify_model =[];

    /**
     * 对于批量保存的model类型，用哪个字段来区分具体的每个model实例，其他model post上来的字段的key必须和该字段的key保持一致
     * 比如如果批量保存model A，post上来的字段有name，position，如果用multi_model_identity_column=name，那么以下的每一行就
     * 看做一个model实例
     * name[0] position[0]
     * name[a] postition[a]
     *
     * 如果只处理一个model类型，那么只设置具体的post 字段名即可，注意不要[]部分
     * 如果有多个model类型，那么格式是['alias'=>'字段名',"alias1"=>"字段名"]
     *
     * 字段名是model原始的字段名
     *
     * @var array
     */
    public $multi_model_identity_column    = null;

    /**
     * 如果一次保存多个对象，对象之间可能存在先后保存关系，则通过save_order来字段model的先后保存
     * 格式[别名，别名]
     *
     * 如果多个对象自己有外键关系，可通过$set_column_value 设置好对象之间的关联字段
     *
     * @var array
     */
    public $save_order = [];
    /**
     * 保存前对model进行二次处理，比如设置某些字段值等；设置后返回该model
     * 如果不保存该model则返回null
     * 如果是批量保存，回调函数会传入该model的index :function($model, $index){}
     *
     * 如果同时保存多个model，那么自行在函数中判断model类型
     *
     *
     * 也可以在该回调中进行数据验证处理，抛出异常
     * @var null
     */
    public $filter_model_hanlder = null;

    /**
     * 每个model保存（调用Save后）后调用的回调函数:function($model, $index){}
     * 如果同时保存多个model，那么自行在函数中判断model类型
     * @var null
     */
    public $after_save = null;

    private $post_data;
    /**
     * 每个alias及其对应的model对象实例，格式["u"=>[key=>userObject]];
     * 如果同一个model类型由多个对象实例，key就是$multi_model_identity_column中这是的post上来的数据的key
     * 没有指定$multi_model_identity_column，key就是0，这个格式是考虑批量保存某个model类型和只保存一个都统一一个格式处理
     * @var
     */
    private $alias_objects;



    public function getModel($alias, $index){
        return $this->alias_objects[$alias][$index];
    }

    /**
     * 按照设置的规则对提交的数据做保存处理，并返回保存后的对象，如果有多个对象，则返回数组，key是alias_class中指定的别名，格式如下：
     * [alias=>[id=>model]], 如果只有一个model，则返回model自己
     * @param array post_data 如果不传入，这通过request 获取post数据，如果传入，则以post来进行处理
     * @throws YZE_FatalException 过程中出错则抛出异常
     * @return YZE_Model
     */
    public function save($post_data=[]){
        $this->post_data = $post_data ?: YZE_Request::get_instance()->the_post_datas();

        //对于只有一个model类型时并没有设置alias的特殊处理, 固定一个为空的alias
        if (! is_array(  $this->alias_classes) || (count( $this->alias_classes)==1 && is_numeric(key( $this->alias_classes )))){
            $this->alias_classes = (array)$this->alias_classes;
            $this->alias_classes = [""=>reset($this->alias_classes)];
            $format_rules = [];
            foreach($this->valid_column_and_rules as $column=>$rule){
                $format_rules[""][$column] = $rule;
            }
            $this->valid_column_and_rules = $format_rules;
            if ($this->fetch_modify_model) $this->fetch_modify_model = [""=>$this->fetch_modify_model];
            if($this->multi_model_identity_column){
                $multi_model_identity_column = (array)$this->multi_model_identity_column;
                $this->multi_model_identity_column = [""=>reset($multi_model_identity_column)];
            }
        }

        if( ! $this->save_order){
            $this->save_order = array_keys($this->alias_classes);
        }

        foreach((array)$this->multi_model_identity_column as $alias=>&$check_column){
            $check_column = ($alias ? $alias."-" : "").$check_column;
            if ( ! is_array( $this->post_data[$check_column] ))
                throw new YZE_FatalException("multi_model_identity_column 设置的 {$check_column} 上传数据格式不合法，需要数组");
        }

        //先创建好对象, 如果设置了$fetch_modify_model，那么对应的model 先查询出来
        $this->alias_objects = [];
        foreach ($this->alias_classes as $alias => $class){
            if ($this->fetch_modify_model[$alias]){
                $modifyObj = $this->fetch_modify_model[$alias]();
                if ( ! $this->multi_model_identity_column[ $alias ]){
                    $this->alias_objects[ $alias ][0] = $modifyObj;
                }else{
                    $this->alias_objects[ $alias ] = $modifyObj;
                }
                if ($this->multi_model_identity_column[ $alias ] && !is_array($this->alias_objects[ $alias ])){
                    throw new YZE_FatalException("fetch_modify_model 返回的{{$this->alias_classes[$alias]}}必须是数组，并且其key需要和 {$this->multi_model_identity_column[ $alias ]} 的key保持一致");
                }
            }

            //统一model类型批量保存的情况
            if ($this->multi_model_identity_column[ $alias ]){
                $multi_name = ($alias ? $alias."-" : "").$this->multi_model_identity_column[ $alias ];
                foreach ( array_keys( $this->post_data[ $multi_name ] ) as $key){
                    if( ! $this->alias_objects[ $alias ][$key] ){
                        $this->alias_objects[ $alias ][$key] = new $class();
                        //这里是按照易点互联设计规范必须有的字段
                        $this->alias_objects[ $alias ][$key]->set("created_on", date("Y-m-d H:i:s"));
                        $this->alias_objects[ $alias ][$key]->set("modified_on", date("Y-m-d H:i:s"));
                        $this->alias_objects[ $alias ][$key]->set("is_deleted", 0);
                        $this->alias_objects[ $alias ][$key]->set("uuid",    YZE_Model::uuid());
                    }
                }
            }else {
                if (!$this->alias_objects[$alias][0]) {
                    $this->alias_objects[$alias][0] = new $class();
                    $this->alias_objects[$alias][0]->set("created_on", date("Y-m-d H:i:s"));
                    $this->alias_objects[$alias][0]->set("modified_on", date("Y-m-d H:i:s"));
                    $this->alias_objects[$alias][0]->set("is_deleted", 0);
                    $this->alias_objects[$alias][0]->set("uuid", YZE_Model::uuid());
                }
            }

        }

        //给对象设置值
        foreach($this->post_data as $column=>$value){
            $columns = explode("-", $column);
            $alias   = @$columns[1] ? $columns[0] : "";
            $field   = @$columns[1] ? $columns[1] : $columns[0];
            if($field=="uuid" || $field=="id")continue;//自动生成的值忽略
            $values  = (array)$value;
            foreach ($this->alias_objects[ $alias ] as $key => &$object){

                if( $object->has_column($field)){
                    $object->set($field, trim($values[$key]));
                }
            }
        }

        //数据验证
        foreach ($this->valid_column_and_rules as $alias=>$column_rules){
            foreach($column_rules as $column => $rules){
                if ( is_array( $rules )){
                    foreach ($rules as $rule=>$error_msg){
                        $this->column_valid($alias,  $column, $rule, $error_msg);
                    }
                }else{
                    $this->column_valid($alias,  $column, $rules);//$rules 是一个lambda 函数
                }
            }
        }

        //保存
        foreach( $this->save_order as $alias ){
            $this->saveModel($alias);
        }

        if( count($this->alias_objects)==1) {
            return reset(reset($this->alias_objects));
        }
        $saved_objects = [];
        foreach($this->alias_objects as $alias=> $objects){
            $saved_objects[$alias] = [];
            foreach ($objects as $key=>$object){
                $saved_objects[$alias][ $object->id ] = $object;
            }
        }
        return $saved_objects;
    }

    /**
     * 保存具体的某个model，如果该model有字段是其他model的值，那么会先保存其他model
     * @param $alias
     * @param $key
     */
    private function saveModel($alias){
        foreach ($this->alias_objects[$alias] as $index => &$object){
            if ($this->filter_model_hanlder){
                $object = call_user_func($this->filter_model_hanlder,  $object, $index);
            }
            if ($object){
                $object->save();
                $this->alias_objects[$alias][$index] = $object;

                //保存后的回调after_save
                if($this->after_save){
                    call_user_func($this->after_save,  $object, $index);
                }
            }
        }
    }

    /**
     * @param string $alias model对应的别名
     * @param string $column 验证的字段名
     * @param []|function $rule 验证规则 可能是一个数组，格式如下["rule"=>"规则"，"msg"=>"错误提示信息"]， 或者是一个lambda 函数 需要传入参数model，和当前验证字段提交上来的值
     * @param string $error_msg 默认提供的验证对账的错误消息，如果rule是一个lambda函数，则没有error msg, 由lambda函数处理
     */
    private  function column_valid($alias, $column, $rule, $error_msg=""){
        $column_name = $this->format_key_name( $alias, $column) ;
        $values = (array)$this->post_data[ $column_name ];
        foreach ( $this->alias_objects[$alias] as $key=>$model ){
            if(is_callable( $rule )){
                call_user_func($rule, $model, $values[$key]);
            }else{
                $method = "do_valid_by_{$rule}";
                call_user_func([$this,$method], $alias, $column, $key, $error_msg);
            }
        }
    }

    private function do_valid_by_not_empty($alias, $column, $key, $error_msg){
        $column_name = $this->format_key_name( $alias, $column) ;
        $values = (array)$this->post_data[ $column_name ];

        if ( ! $values[$key]){
            throw new YZE_FatalException($error_msg);
        }
    }

    private function do_valid_by_unique($alias, $column, $key, $error_msg){
        $column_name = $this->format_key_name( $alias, $column) ;
        $values = (array)$this->post_data[ $column_name ];

        $model = $this->alias_objects[$alias][$key];
        $value = $values[$key];
        $checkModel = $this->alias_classes[$alias]::from()->where("is_deleted=0 and {$column}=:v")->getSingle([":v"=>$value]);
        if ($checkModel && $checkModel->id != $model->id) throw new YZE_FatalException($error_msg);

    }

    private function format_key_name($alias,$column){
        return ($alias ? $alias."_" : "").$column;
    }


    public function submit_notify($post_data){
        $loginUser = YZE_Hook::do_hook(YZE_HOOK_GET_LOGIN_USER);

        $save_helper = new Save_Model_Helper();
        $save_helper->alias_classes = Notify_Model::CLASS_NAME;
        $save_helper->valid_column_and_rules = [
            Notify_Model::F_SUBJECT => [Save_Model_Helper::RULE_NOT_EMPTY => "通知标题不能为空"],
            Notify_Model::F_BODY => [Save_Model_Helper::RULE_NOT_EMPTY => "通知内容不能为空"],

        ];



        $save_helper->filter_model_hanlder = function ($model,$index) use($post_data,$loginUser){
            $model->set(Notify_Model::F_UUID,uuid());
            $model->set(Notify_Model::F_USER_ID,$loginUser->id);
            $model->set(Notify_Model::F_ORG_ID,$loginUser->org_id);
            $model->set(Notify_Model::F_ORG_NAME,$loginUser->get_org()->name());
            $model->set(Notify_Model::F_IS_FOCUS,$post_data['focus']?1:0);
            if ($post_data["to_org_id"]) {
                $model->set(Notify_Model::F_TARGET_ORG_IDS, implode(",", $post_data["to_org_id"]));
            }
            //获取时间范围
            if ($post_data['timeHorizon']){
                $timeHorizon = explode("~",$post_data['timeHorizon']);
                //设置开始时间
                $startTime = trim($timeHorizon[0]);
                $model->set(Notify_Model::F_FROM_DATE,$startTime);
                //设置结束时间
                $endTime = trim($timeHorizon[1]);
                $model->set(Notify_Model::F_TO_DATE,$endTime);
            }

            return $model;
        };

        $save_helper->save();
    }
}