// pages/deliver/deliver.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    name: '佰川回收 181-9857-5678',
    address: '贵州省贵阳市南明区和丰大厦商城-1楼1号佰川回收',
    orderlistnum:'',
    exprss_company:'',
    consignor:'',
    consignor_address:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var oid = options.oid;
    var that = this;
    that.setData({
      ordid: oid
    })
  },
  exprssCompany:function(e){
    this.data.exprss_company = e.detail.value
  },
  orderlistnuminput: function(e) {
    this.data.orderlistnum = e.detail.value
  },
  sumbit:function(){
    var ooid = this.data.ordid;
    wx.request({
      url: app.NEW_API + "/api/up_order_status",
      data: {
        id: ooid,
        status: 'shipped',
        express_num: this.data.orderlistnum,
        express_company:this.data.exprss_company,
        consignor:this.data.consignor,
        address: this.data.consignor_address
      },
      method: 'get',
      dataType: 'json',
      success: function (res) {
        if (res.data.success) {
          wx.switchTab({
            url: '../person/index',
          })
        } else {
          wx.showModal({
            title: "信息提示",
            content: " 连接网络失败！！！"
          })
        }
      }
    })
  },
  getAddress: function() {
    var that = this;
    wx.chooseAddress({
      success: function(res) {
        var usemessage = res;
        that.data.consignor = usemessage.userName + " " + usemessage.telNumber;
        that.data.consignor_address = usemessage.provinceName + usemessage.cityName + usemessage.countyName + usemessage.detailInfo;
        that.setData({
          addressList: usemessage
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