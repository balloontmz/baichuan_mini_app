// pages/sigin/sigin.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    sign_img:""
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

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
    var that = this;
    var page_id = 0;
    wx.getSystemInfo({
      success: function(res) {
        that.setData({
          height: res.windowHeight,
          width: res.windowWidth
        })
        wx.showLoading({
          title: '请稍等...',
          mask: true,
          success: function(res) {
            wx.request({
              url: app.NEW_API + "/api/sign_img.json",
              method: "post",
              data: {
                wx_appid: app.wx_appid
              },
              header: {
                'content-type': 'application/x-www-form-urlencoded'
              },
              success: function (res) {
                that.setData({
                  sign_img: res.data.data.sign_img
                })
                app.getWXUserInfo(page_id) //获取code调用登录
              },
            })
           
          },
          fail: function(res) {},
          complete: function(res) {},
        })
      }
    })
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