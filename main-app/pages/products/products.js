const app = getApp()
Page({
  data: {
    cisd: 1,
    uuid: 7,
    cateList: [{
      classname: "手机",
      id: 1
    }, {
      classname: "平板",
      id: 2
    }, {
      classname: "笔记本",
      id: 3
    }],
    brandList: [{
      productList: []
    }],
    cid: '',
    bid: "",
    num: 0,
    hasMore: 0,
    brandScrollTop: 0,
    productScrollTop: 0,
    left: 0,
    iconSearch: "../../img/icon-search.svg",
    jump_id: null
  },
  onLoad: function(option) {
    var that = this;
    wx.request({
      url: app.NEW_API + "/api/first_product.json",
      method: "post",
      data: {
        type_id: 1
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res) {
        var f_id = res.data.data[0].id;
        that.setData({
          cisd: 11,
          brandList: res.data.data,
          first_product_id: res.data.data[0].id
        })
        wx.request({
          url: app.NEW_API + "/api/product.json",
          method: "post",
          data: {
            first_product_id: f_id
          },
          header: {
            'content-type': 'application/x-www-form-urlencoded'
          },
          success: function(res) {
            that.setData({
              productList: res.data.data
            })
          },
        })
      },
      fail: function(res) {},
      complete: function(res) {},
    })
  },

  //点击键盘上的搜索
  goSearch: function(e) {
    // console.log(e.detail.value);
    wx.navigateTo({
      url: '../search/search',
    })
  },
  cateTapHandler: function(e) {
    var id = e.target.dataset.cid;
    // wx.setStorageSync('jump_id', id)
    var arr = [11, 12, 13];
    var crurrnt = false;
    for (var i = 0; i < arr.length; i++) {
      if (id == arr[i]) {
        var cisd = arr[i]
      }
    }
    var that = this;
    wx.request({
      url: app.NEW_API + "/api/first_product.json",
      method: "post",
      data: {
        type_id: id
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res) {
        var f_id = res.data.data[0].id;
        that.setData({
          brandList: res.data.data,
          first_product_id: res.data.data[0].id,
          cisd: id
        })
        wx.request({
          url: app.NEW_API + "/api/product.json",
          method: "post",
          data: {
            first_product_id: f_id
          },
          header: {
            'content-type': 'application/x-www-form-urlencoded'
          },
          success: function(res) {
            that.setData({
              productList: res.data.data
            })
          },
        })
      }
    })
  },
  brandTapHandler: function(e) {
    var id = e.target.dataset.bid;
    var that = this;
    var arr1 = new Array(100);
    for (var i = 0; i < arr1.length; i++) {
      arr1[i] = i;
    }
    for (var a = 0; a < arr1.length; a++) {
      if (id == arr1[a]) {
        var uuid = arr1[a]
      }
    }
    wx.request({
      url: app.NEW_API + "/api/product.json",
      method: "post",
      data: {
        first_product_id: id
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res) {
        that.setData({
          productList: res.data.data,
          first_product_id: id
        })
      },
    })
  },
  onShow: function() {
    var that = this;
    var nickName = wx.getStorageSync("nickName");
    var cellphone = wx.getStorageSync("cellphone");
    if (!nickName) {
      wx.switchTab({
        url: "../person/index",
      })
    } else if (cellphone==null) {
      wx.showModal({
        title: '提示',
        content: '为了给您提供更好的服务，请先前往个人中心绑定手机号哦！',
        cancelText: '暂不',
        confirmText: '前往',
        success(res) {
          if (res.confirm) {
            wx.switchTab({
              url: '../person/index',
            })
          } else if (res.cancel) {
            wx.switchTab({
              url: '../person/index',
            })
          }
        }
      })
    } else {
      return;
    }
  },
  onShareAppMessage: function(t) {}
});