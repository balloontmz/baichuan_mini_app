// pages/person/index.js
const app = getApp();
var util = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    routers: [{
        name: '待发货',
        url: '../my-orderlist/my-orderlist?status=unshipped',
        icon: '../../img/person/dai.png',
        id: "001"
      }, {
        name: '已发货',
        url: '../my-orderlist/my-orderlist?status=shipped',
        icon: '../../img/person/yifa.png',
        id: "002"
      }, {
        name: '待验机',
        url: '../my-orderlist/my-orderlist?status=looking',
        icon: '../../img/person/dwc.png',
        id: "003"
      },
      {
        name: '待收款',
        url: '../my-orderlist/my-orderlist?status=collect',
        icon: '../../img/person/dsk.png',
        id: "003"
      }, {
        name: '已完成',
        url: '../my-orderlist/my-orderlist?status=finish',
        icon: '../../img/person/wan.png',
        id: "003"
      }, {
        name: '待退回',
        url: '../my-orderlist/my-orderlist?status=reback',
        icon: '../../img/person/dth.png',
        id: "004"
      }, {
        name: '已退回',
        url: '../my-orderlist/my-orderlist?status=returned',
        icon: '../../img/person/yth.png',
        id: "006"
      }, {
        name: '地址管理',
        url: '../address/address',
        icon: '../../img/person/dizhi.png',
        id: "006"
      }, {
        name: '全部订单',
        url: '../myallorder/myallorder',
        icon: '../../img/person/qbdd.png',
        id: "006"
      }
    ]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

  },
  /**
   * 长按复制
   */
  copy: function(e) {
    var that = this;
    wx.setClipboardData({
      data: '收货人：佰川贵州回收部，电话：181-9857-5678， 地址：贵州省贵阳市南明区和丰大厦商城-1楼1号',
      success: function(res) {
        console.log(res);
        wx.showToast({
          title: '复制成功',
        });
      }
    });
  },
  bindGetUserInfo: function() {
    var that = this;
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function(res) {
              var page_id = 1;
              var userInfo = res.userInfo;
              app.globalData.wx_user_info.nickName = userInfo.nickName;
              wx.setStorageSync("nickName", userInfo.nickName);
              app.globalData.wx_user_info.avatar = userInfo.avatarUrl;
              app.globalData.wx_user_info.gender = userInfo.gender;
              app.getWXUserInfo(page_id); //存信息
              that.setData({
                wx_user_info: res.userInfo
              })
            }
          })
        }
      }
    })
  },
  /**
   * 获取用户手机号
   */
  getPhoneNumber(e) {
    var that = this;
    var data = {
      session_key: app.globalData.wx_user_info.session_key,
      iv: e.detail.iv,
      encryptedData: e.detail.encryptedData,
      openid: wx.getStorageSync('openid')
    }
    wx.login({
      success: (res) => {
        util.post(app.NEW_API + '/api/cellphone.json', data, (res) => {
          app.globalData.wx_user_info.cellphone = res.phoneNumber;
          wx.setStorageSync("cellphone", res.phoneNumber);
          that.setData({
            cellphone: res.phoneNumber
          })
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    if (app.globalData.wx_user_info.nickName == null) {
      wx.showToast({
        title: '请先授权登录!',
        icon: 'none',
        duration: 3000,
        mask: true,
      })
    } else {
      this.setData({
        wx_user_info: app.globalData.wx_user_info
      })
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})