const app = getApp();
const API = app.API;


let utils = {
  /**
   * 格式化显示日期
   */
  formatDate: function (date) {
    const year = date.getFullYear()
    const month = date.getMonth() + 1
    const day = date.getDate();
    return [year, month, day].map(this.formatNumber).join('-');
  },
  /**
   * 格式化显示日期
   */
  formatTime: function (date) {
    const year = date.getFullYear()
    const month = date.getMonth() + 1
    const day = date.getDate()
    const hour = date.getHours()
    const minute = date.getMinutes()
    const second = date.getSeconds()

    return [year, month, day].map(this.formatNumber).join('-') + ' ' + [hour, minute, second].map(this.formatNumber).join(':')
  },

  formatNumber: function (n) {
    n = n.toString()
    return n[1] ? n : '0' + n
  },
  /**
   * 带有确认的弹窗
   */
  confirmModal: function (msg = "提示", title = "提示", confirm = null, cancel = null) {
    wx.showModal({
      title: title,
      content: msg,
      confirmText: "确定",
      cancelText: "取消",
      success: function (res) {
        if (res.confirm) {
          if (confirm) {
            confirm()
          }
        } else {
          if (cancel) {
            cancel()
          }
        }
      }
    });
  },

  /**
   * 只有一个确定的弹窗
   */
  alertModal: function (msg = "提示") {
    wx.showModal({
      content: msg,
      showCancel: false,
      success: function (res) {
        if (res.confirm) {
          console.log('用户点击确定')
        }
      }
    });
  },

  /**
   * 显示提示
   */
  showToast: function (title, flag) {
    var that = this;
    if (flag) {
      wx.showToast({
        title: title + "",
      }, 2000);
    } else {
      wx.showToast({
        title: title + "",
        icon: "none"
      }, 2000);
    }
    setTimeout(function () {
      wx.hideToast();
    }, 2000);
  },

  /**
   * post请求
   */
  post: function (url, data, success, fail) {
    wx.showLoading({
      title: '请稍等...',
    });

    let header = {
      "YDHL-ASSET-WX-USER-OPENID": app.globalData.wx_user_info.openid,
      'content-type': 'application/x-www-form-urlencoded'
    };
    let that = this;

    wx.request({
      url: url, //获取消息的接口
      method: "post",
      header: header,
      data: data,
      success: function (ret) { //返回的参数包括{cookies,data,errMsg,header,statusCode,....}
        wx.hideLoading();
        ret = ret.data;
        if (ret.success) {
          typeof success == "function" && success(ret.data); //以返回的data作为参数调用回调函数
        } else {
          if (fail && typeof fail == "function")
            fail(ret.msg);
          else
            that.showToast(ret.msg, false);
        }
      },
      fail: function () {
        wx.hideLoading();
        that.showToast('系统错误，请退出重试', false);
      }
    });
  },

  /**
   * 因为get是关键字
   */
  get: function (url, data, success, fail) {
    let that = this;
    let header = {
      "YDHL-ASSET-WX-USER-OPENID": app.globalData.wx_user_info.openid
    };
    wx.request({
      url: url, //获取消息的接口
      method: "get",
      header: header,
      data: data,
      success: function (ret) { //返回的参数包括{cookies,data,errMsg,header,statusCode,....}
        wx.hideLoading();
        ret = ret.data;
        if (ret.success) {
          typeof success == "function" && success(ret.data); //以返回的data作为参数调用回调函数
        } else {
          if (fail && typeof fail == "function")
            fail(ret);
          else
            that.showToast(ret.msg, false);
        }
      },
      fail: function () {
        wx.hideLoading();
        that.showToast('系统错误，请退出重试', false);
      }
    });
  },

  /**
   * 检查用户信息是否有效
   */
  checkLogin: function () {
    let res = true;
    // 判断app
    if (!app.globalData.hasLogin) {
      console.log("safa");
      this.confirmModal("您还没有登录，请登录后重试", "提示", function () {
        // 去登录页面
        // wx.navigateTo({
        //   url: '',
        // })
      })
      res = false;
    }
    // TODO 判断平台登录

    // TODO 判断登录信息

    return res;

  },
  uniq: function (array) {
    var temp = []; //一个新的临时数组
    for (var i = 0; i < array.length; i++) {
      if (temp.indexOf(array[i]) == -1) {
        temp.push(array[i]);
      }
    }
    return temp;
  },

  /**
   * 计算图片
   */
  imageUtil: function (e, size = 0.5) {
    var imageSize = {};
    var originalWidth = e.width; //图片原始宽  
    var originalHeight = e.height; //图片原始高  
    var originalScale = originalHeight / originalWidth; //图片高宽比  
    //获取屏幕宽高  
    wx.getSystemInfo({
      success: function (res) {
        var windowWidth = res.windowWidth;
        imageSize.imageWidth = windowWidth * size;
        imageSize.imageHeight = originalScale * windowWidth * size;
      }
    })
    return imageSize;
  },
  getIndex: function (arrays, obj) {
    var i = arrays.length;
    while (i--) {
      if (arrays[i] == obj) {
        return i;
      }
    }
    return false;
  },
  /**
   * 计算距离
   */
  distance: function (la1, lo1, la2, lo2) {
    var La1 = la1 * Math.PI / 180.0;
    var La2 = la2 * Math.PI / 180.0;
    var La3 = La1 - La2;
    var Lb3 = lo1 * Math.PI / 180.0 - lo2 * Math.PI / 180.0;
    var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(La3 / 2), 2) + Math.cos(La1) * Math.cos(La2) * Math.pow(Math.sin(Lb3 / 2), 2)));
    s = s * 6378.137;
    s = Math.round(s * 10000) / 10000;
    s = s.toFixed(2);
    return s;
  },


  /**
   * 根据坐标打开微信内置地图
   */
  openMap: function (latitude, longitude) {
    wx.openLocation({
      latitude,
      longitude,
      scale: 18
    })
  },

  unique: function (arr) {
    for (var i = 0; i < arr.length; i++) {
      for (var j = i + 1; j < arr.length; j++) {
        if (arr[i].toString() == arr[j].toString()) { //第一个等同于第二个，splice方法删除第二个
          arr.splice(j, 1);
          j--;
        }
      }
    }
    return arr;
  }
}
module.exports = utils;