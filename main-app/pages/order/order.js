// pages/order/order.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    order_id: "",
    countDay: null,
    countHour: null,
    countMinute: null,
    countSecond: null,
    counts: 1,
    price_total: 0,
    order_id: ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var order_id = options.orderid;
    this.data.order_id = options.orderid;
    // wx.setStorageSync('order_id', order_id)
    var that = this;
    wx.request({
      url: app.NEW_API + "/api/order_info",
      data: {
        order_id: order_id,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      method: 'post',
      success: function(res) {
        that.data.order_id = res.data.data.id
        that.setData({
          orderinfo: res.data.data,
          price_total: res.data.data.price,
          counts: res.data.data.goods_count,
        })
      }
    })
    that.setData({
      orderid: order_id
    })
  },
  goProduct: function() {
    wx.request({
      url: app.NEW_API + "/api/up_order",
      data: {
        id: this.data.order_id,
        counts: this.data.counts,
        price: this.data.price_total
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      method: 'post',
      success: function(res) {
        wx.switchTab({
          url: '../products/products',
        })
      }
    })
  },
  goDeliver: function() {
    wx.request({
      url: app.NEW_API + "/api/up_order",
      data: {
        order_id: this.data.order_id,
        counts: this.data.counts,
        price: this.data.price_total
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        wx.navigateTo({
          url: '../deliver/deliver?oid=' + this.data.order_id,
        })
      }
    })

  },
  goIndex: function() {
    wx.request({
      url: app.NEW_API + "/api/up_order",
      data: {
        order_id: this.data.order_id,
        counts: this.data.counts,
        price: this.data.price_total
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        wx.switchTab({
          url: '../welcome/welcome',
        })
      }
    })
  },
  del: function() {
    wx.switchTab({
      url: '../welcome/welcome',
    })
  },
  addgoodscount: function() {
    var that = this;
    var add = this.data.counts;
    ++add;
    var total_p = this.data.price_total * add;
    that.setData({
      counts: add,
      price_total: total_p
    })
  },
  reducegoodscount: function() {
    var that = this;
    var add = this.data.counts;
    --add;
    var total_p = this.data.price_total * add;
    that.setData({
      counts: add,
      price_total: total_p
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {
    this.setData({
      countDay: '00',
      countHour: '24',
      countMinute: '00',
      countSecond: '00',
    });

    // 设置开始时间，1天=86400秒
    var totalSecond = 86400 - 1;

    // 设置定时器
    var timeInterval = setInterval(function() {
      // 天
      var day = Math.floor(totalSecond / 3600 / 24);
      if (day < 10) day = '0' + day;

      // 时
      var hour = Math.floor((totalSecond - day * 3600 * 24) / 3600);
      if (hour < 10) hour = '0' + hour;

      // 分
      var minute = Math.floor((totalSecond - day * 3600 * 24 - hour * 3600) / 60);
      if (minute < 10) minute = '0' + minute;

      // 秒
      var second = totalSecond - day * 3600 * 24 - hour * 3600 - minute * 60;
      if (second < 10) second = '0' + second;

      this.setData({
        countDay: day,
        countHour: hour,
        countMinute: minute,
        countSecond: second,
      });
      totalSecond--;
      if (totalSecond < 0) {
        clearInterval(timeInterval);
        wx.showToast({
          title: '倒计时结束',
        });
        this.setData({
          countDay: '00',
          countHour: '00',
          countMinute: '00',
          countSecond: '00',
        });
      }
    }.bind(this), 1000);
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