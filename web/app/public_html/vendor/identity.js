/** 
 * 身份证15位编码规则：dddddd yymmdd xx p
 * dddddd：地区码
 * yymmdd: 出生年月日
 * xx: 顺序类编码，无法确定
 * p: 性别，奇数为男，偶数为女
 * <p />
 * 身份证18位编码规则：dddddd yyyymmdd xxx y
 * dddddd：地区码
 * yyyymmdd: 出生年月日
 * xxx:顺序类编码，无法确定，奇数为男，偶数为女
 * y: 校验码，该位数值可通过前17位计算获得
 * <p />
 * 18位号码加权因子为(从右到左) wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ]
 * 验证位 Y = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ]
 * 校验位计算公式：Y_P = mod( ∑(Ai×wi),11 )
 * i为身份证号码从右往左数的 2...18 位; Y_P为校验码所在校验码数组位置
 *
 */
var Identity = function() {
	// 区域ID
	var areaMap = { 11:"北京市", 12:"天津市", 13:"河北省", 14:"山西省", 15:"内蒙古自治区", 
					21:"辽宁省", 22:"吉林省", 23:"黑龙江省",
					31:"上海市", 32:"江苏省", 33:"浙江省", 34:"安徽省", 35:"福建省", 36:"江西省", 37:"山东省",
					41:"河南省", 42:"湖北省", 43:"湖南省", 44:"广东省", 45:"广西壮族自治区", 46:"海南省", 
					50:"重庆市", 51:"四川省", 52:"贵州省", 53:"云南省", 54:"西藏自治区", 
					61:"陕西省", 62:"甘肃省", 63:"青海省", 64:"宁夏回族自治区",65:"新疆维吾尔自治区",
					71:"台湾省", 
					81:"香港特别行政区", 82:"澳门特别行政区", 
					91:"国外"};
	// 男女ID
	var sexMap = {0:"女",1:"男"};
	//错误信息
	var result_status = ["", "身份证号码位数不对!", "身份证号码出生日期超出范围或含有非法字符!", "身份证号码校验错误!", "身份证地区非法!"];

	/**
	 * 解析身份证
	 * @param string identity
	 */
	var initIdentity = function ( identity ) {
		var datas = {
			"status" : false,
			"message" : "身份证不能为空!",
			"birthday" : "",
			"gander" : "",
			"area" : "",
			"identity" : identity
		};
		if ( identity ) {
			identity = identity.toLowerCase();
			var result = checkIdentity ( identity );
			if ( ! result ) {
				datas.area = getArea ( identity );
				datas.gander = getSex ( identity );
				datas.birthday = getBirthday ( identity );
				datas.status = true;
			}
			datas.message = result;
		}
		return datas;
	}
	
	/**
	 * 验证ID，正确返回""，错误则返回错误信息
	 * @param string identity
	 */
	var checkIdentity = function ( identity ) {
		//去掉首尾空格
	    identity = trim( identity.replace ( / /g, "" ) );
		
		if ( identity.length == 15 || identity.length == 18 ) {
			if ( ! checkArea ( identity ) ) {
				return result_status [4];
			} else if ( ! checkBrith ( identity ) ) {
				return result_status [2];
			} else if ( identity.length == 18 && ! check18Code ( identity ) ) {
				return result_status [3];
			} else {
				return result_status [0];
			}
	    } else {
			//不是15或者18，位数不对
	        return result_status [1];
	    }
	}

	/**
	 * 得到地区码代表的地区 
	 * @param string identity 正确的15/18位身份证号码
	 */
	var getArea = function ( identity ) {
		return areaMap [ parseInt ( identity.substr ( 0, 2 ) ) ];
	}

	/** 
	 * 通过身份证得到性别
	 * @param string identity 正确的15/18位身份证号码
	 * @return 女、男
	 */
	var getSex = function ( identity ) {
	    if ( identity.length == 15 ) {
	        return sexMap [ identity.substring (14, 15) % 2 ];
	    } else if (identity.length == 18) {
	        return sexMap [ identity.substring (14, 17) % 2 ];
	    } else {
			//不是15或者18,null
	        return null;
	    }
	}

	/**
	 * 得到生日"yyyy-mm-dd"
	 * @param string identity 正确的15/18位身份证号码
	 */
	var getBirthday = function ( identity ) {
		var birthdayStr;
	    if ( 15 == identity.length ) {
	        birthdayStr = identity.charAt (6) + identity.charAt (7);
			
	        if ( parseInt ( birthdayStr ) < 10 ) {
	            birthdayStr = '20' + birthdayStr;
	        } else {
	            birthdayStr = '19' + birthdayStr;
	        }
	        birthdayStr = birthdayStr + '-' + identity.charAt(8) + identity.charAt(9) + '-' + identity.charAt(10) + identity.charAt(11);
	    }else if (18 == identity.length) {
	        birthdayStr = identity.charAt(6) + identity.charAt(7) + identity.charAt(8) + identity.charAt(9) + '-' + identity.charAt(10) + identity.charAt(11) + '-' + identity.charAt(12) + identity.charAt(13);
	    }
		
		return birthdayStr;
	}

	/**
	 * 验证身份证的地区码
	 * @param string identity 身份证字符串
	 */
	var checkArea = function ( identity ) {
		if ( areaMap [ parseInt ( identity.substr (0, 2) ) ] == null ) {
			return false;
		} else {
			return true;
		}
	}

	/** 
	 * 验证身份证号码中的生日是否是有效生日
	 * @param string identity 身份证字符串
	 * @return
	 */
	var checkBrith = function ( identity ) {
		var result = true;
		
		if ( 15 == identity.length ) {
		    var year = identity.substring(6, 8);
		    var month = identity.substring(8, 10);
		    var day = identity.substring(10, 12);
		    var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
		   
		    // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法  
		    if (temp_date.getYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
		        result =  false;
		    }
		} else if ( 18 == identity.length ) {
		    var year = identity.substring(6, 10);
		    var month = identity.substring(10, 12);
		    var day = identity.substring(12, 14);
		    var temp_date = new Date(year, parseFloat(month) - 1, parseFloat(day));
		    
			// 这里用getFullYear()获取年份，避免千年虫问题  
		    if (temp_date.getFullYear() != parseFloat(year) || temp_date.getMonth() != parseFloat(month) - 1 || temp_date.getDate() != parseFloat(day)) {
		        result = false;
		    }
		} else {
			result = false;
		}
		
		return result;
	}

	/** 
	 * 判断身份证号码为18位时最后的验证位是否正确
	 * @param identity 身份证号码数组
	 * @return
	 */
	var check18Code = function ( identity ) {
		// 加权因子
		var wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
		// 身份证验证位值.10代表X   
		var valideCodeArr = [1, 0, 'x', 9, 8, 7, 6, 5, 4, 3, 2]; 
	    var sum = 0; // 声明加权求和变量
	    
	    for ( var i = 0; i < 17; i ++ ) {
			sum +=  wi [ i ] * identity [ i ];
		}
	    
	    var checkHouParameter = sum % 11;
		if ( valideCodeArr [ checkHouParameter ] != identity [ 17 ] ) {
			return false;
		} else {
			return true;
		}
	}

	//去掉字符串头尾空格  
	var trim = function ( str ) {
	    return str.replace(/(^\s*)|(\s*$)/g, "");
	}
	
	return {
        init: function ( identity ) {
        	return initIdentity ( identity );
        }
    };
}();