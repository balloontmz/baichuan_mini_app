//app.js
App({
  // API: "http://127.0.0.1:8090/",
  API: "https://www.bckj.store/",
  NEW_API:"http://192.168.43.133",
  globalData: {
    jump_tyep: null,
    wx_user_info: {
      wx_code: "",
      openid: "",
      nickName: "",
      avatarUrl: "",
      gender: "",
      cellphone: null,
      name: "",
      status: "",
      session_key: "",
      wx_appid:"wx7717d96c45ce7e7d"
    },
  },


  onLaunch: function () {
  },
  onShow: function () {

  },

  getUseinfo() {
    var that = this;
    wx.getUserInfo({
      success: function (res) {
        var userInfo = res.userInfo;
        that.globalData.wx_user_info.name = userInfo.nickName;
        that.globalData.wx_user_info.avatar = userInfo.avatarUrl;
        that.globalData.wx_user_info.gender = userInfo.gender;
        that.getWXUserInfo(); //获取code
      }
    })
  },
  getWXUserInfo(page_id) {
    var that = this;
    wx.login({
      success(res) {
        if (res.code) {
          that.globalData.wx_user_info.wx_code = res.code;
          that.login(page_id); //信息完全,调用登录
        } else {
          wx.showLoading({
            title: '登录失败!' + res.errMsg,
          });
        }
      }
    });
  },

  /**
   * 登录方法
   */
  login(page_id) {
    let that = this;
    console.log(that.globalData.wx_user_info)
    var page_id = page_id;
    wx.request({
      url: this.NEW_API + "/signin.json", //登录获取信息的接口
      method: "post",
      data: that.globalData.wx_user_info,
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res);
        wx.hideLoading();
        if (res.data.code == 500) {     //后台禁用用户后
          wx.showModal({
            title: '信息提示',
            content: res.data.msg,
          })
          return;
        } else if (res.data) {
          that.globalData.wx_user_info = res.data.data.userInfo;
          that.globalData.hasLogin = true;
          //成功后缓存用户身份信息
          that.saveUserKey();
          if (page_id == 0) {
            wx.switchTab({
              url: '/pages/welcome/welcome',
            })
          } else if (page_id == 1) {
            wx.switchTab({
              url: '/pages/user/index',
            })
          }
        } else {
          wx.showLoading({
            title: "失败"
          });
        }
      },
      fail: function () {
        wx.showToast({
          title: '系统错误,请退出重试',
          icon: 'none'
        }, 5000);
      }
    });
  },
  toLogin: function () {
    var login_model = true;
    wx.switchTab({
      url: '/pages/user/index?login_model=' + login_model,
    })
  },

  //缓存身份识别信息
  saveUserKey() {
    wx.setStorageSync("openid", this.globalData.wx_user_info.openid); //用户身份id
    wx.setStorageSync("cellphone", this.globalData.wx_user_info.cellphone);
    wx.setStorageSync("avatarUrl", this.globalData.wx_user_info.avatarUrl);
    wx.setStorageSync("nickName", this.globalData.wx_user_info.nickName)
  }
})