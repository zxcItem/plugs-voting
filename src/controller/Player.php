<?php

namespace plugin\voting\controller;

use plugin\voting\model\PluginVoteProjectPlayer;
use plugin\voting\model\PluginVoteProject;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 投票选手管理
 * Class Player
 * @package plugin\voting\controller
 */
class Player extends Controller
{

    /**
     * 投票选手管理
     * @auth true
     * @menu true
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index()
    {
        $this->type = $this->get['type'] ?? 'index';
        PluginVoteProjectPlayer::mQuery()->layTable(function () {
            $this->title = '投票选手管理';
            $this->projects = PluginVoteProject::item();
        }, function (QueryHelper $query) {
            $query->with(['projectName'])->like('name')->equal('code,status,is_check')->dateBetween('create_time');
            $query->where(['deleted' => 0, 'status' => intval($this->type === 'index')]);
        });
    }

    /**
     * 数据列表处理
     * @param array $data
     */
    protected function _index_page_filter(array &$data)
    {

    }

    /**
     * 表单数据处理
     * @param array $data
     */
    protected function _form_filter(array &$data)
    {
        if ($this->request->isGet()){
            $this->projects = PluginVoteProject::item();
        }

    }

    /**
     * 添加投票选手
     * @auth true
     */
    public function add()
    {
        PluginVoteProjectPlayer::mForm('form');
    }

    /**
     * 编辑投票选手
     * @auth true
     */
    public function edit()
    {
        PluginVoteProjectPlayer::mForm('form');
    }

    /**
     * 修改投票选手状态
     * @auth true
     */
    public function state()
    {
        PluginVoteProjectPlayer::mSave($this->_vali([
            'status.in:0,1'  => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * 删除投票选手
     * @auth true
     */
    public function remove()
    {
        PluginVoteProjectPlayer::mDelete();
    }

    /**
     * 选手选择
     * @login true
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function select()
    {
        $this->get['status'] = 1;
        $this->index();
    }
}