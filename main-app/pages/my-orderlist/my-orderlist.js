// pages/my-orderlist/my-orderlist.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var openid = wx.getStorageSync('openid');
    var wx_appid = wx.getStorageSync('wx_appid');
    wx.request({
      url: app.NEW_API + "/api/my_order",
      data: {
        openid: openid,
        wx_appid: wx_appid,
        status:options.status
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'post',
      dataType: 'json',
      success: function (res) {
        that.setData({
          orderList: res.data.data,
          cencel_btn:options.status
        })
      }
    })
  },
  cencel_order:function(e){
    var that=this;
    var id = e.currentTarget.dataset.orderid;
    wx.request({
      url: app.NEW_API + "/api/cancel_order",
      data: {
        order_id: id
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'post',
      dataType: 'json',
      success: function (res) {
        var newList = that.data.orderList;
        wx.showToast({
          title: '取消成功',
          icon: 'success',
          duration: 2000
        })
        for (var i = 0; i < newList.length; i++) {
          if (newList[i].id == id) {
            newList.splice(i, 1);
          }
        }
        that.setData({  //主动刷新
          orderList: newList
        })
      }
    })
   
  },

  toDeliver:function(e){
    wx.navigateTo({
      url: '../deliver/deliver?oid=' + e.currentTarget.dataset.oid,
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})