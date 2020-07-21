// pages/search/search.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    iconSearch: "../../img/icon-search.svg",
    keyw: '',
    isValidTel: !1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },
  getKeyw: function (e) {
    var that = this;
    wx.request({
      url: app.NEW_API + "/api/query_product.json",
      method: "post",
      data: {
        query: e.detail.value,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        that.setData({
          phoneList: res.data.data
        })
      }
    })
  },
  gowelcome: function () {
    wx.switchTab({
      url: '../welcome/welcome',
    })
  },
  goProduct: function (e) {
    var id = e.currentTarget.dataset.id;
    var name = e.currentTarget.dataset.name;
    var comment = e.currentTarget.dataset.comment;
    wx.navigateTo({
      url: '../product/product?id=' + id + '&name=' + name + '&comments=' + comment,
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