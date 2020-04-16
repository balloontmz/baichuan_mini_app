<?php
class Identity {
	
	/**
	 * 身份证号码
	 * @var string
	 */
	private $identity = "";
	/**
	 * 省市县（6位）
	 * @var string
	 */
	private $areaNum = "";
	/**
	 * 出生年月（6位）
	 * @var string
	 */
	private $dateNum = "";
	/**
	 * 性别（3位）
	 * @var string
	 */
	private $sexNum = "";
	/**
	 * 校验码（1位）
	 * @var string
	 */
	private $endNum = "";
	/**
	 * 身份证是否有效
	 * @var bool
	 */
	private $valid = false;

	/**
	 * 身份证是否有效
	 * @return boolean
	 */
	public function isValid () {
		return $this->valid;
	}

	/**
	 * 获取身份证
	 * @return string
	 */
	public function getIdentity () {
		return $this->identity;
	}

	/**
	 * 获取身份证地区编号
	 * @return string
	 */
	public function getAreaCode () {
		if ( ! $this->valid ) return "";
		return $this->areaNum;
	}

	/**
	 * 获取出生日期 yyyy-mm-dd
	 * @return string
	 */
	public function getBirthday () {
		if ( ! $this->valid ) return "";
		$year = substr ( $this->dateNum, 0, 4 );
		$month = substr ( $this->dateNum, 4, 2 );
		$day = substr ( $this->dateNum, 6, 2 );
		return "{$year}-{$month}-{$day}";
	}

	/**
	 * 获取年龄 array( 'year'=>'', 'month'=>'', 'day'=>'' )
	 * @return array
	 */
	function getAge () {
		$now = date ( "Y-m-d" );
		$birthday = $this->getBirthday ();
		if ( strtotime ( $birthday ) > strtotime ( $now ) ) return array ();
		
		list ( $Y1, $m1, $d1 ) = explode ( '-', $birthday );
		list ( $Y2, $m2, $d2 ) = explode ( '-', $now );
		$y = $Y2 - $Y1;
		$m = $m2 - $m1;
		$d = $d2 - $d1;
		if ( $d < 0 ) {
			$d += ( int ) date ( 't', strtotime ( "-1 month $now" ) );
			$m --;
		}
		if ( $m < 0 ) {
			$m += 12;
			$y --;
		}
		return array (
			'year' => $y,
			'month' => $m,
			'day' => $d
		);
	}

	/**
	 * 验证出生日期
	 * @param string $year
	 * @param string $month
	 * @param string $day
	 * @return boolean
	 */
	public function checkBirthday ( $year, $month, $day ) {
		if ( $year && $month && $day ) {
			$date = $this->getBirthday ();
			$_date = explode ( "-", $date );
			try {
				if ( intval ( $year ) == intval ( $_date [ 0 ] ) && intval ( $month == intval ( $_date [ 1 ] ) && intval ( $day ) == $_date [ 2 ] ) ) return true;
			} catch ( Exception $e ) {
				return false;
			}
		}
		return false;
	
	}

	/**
	 * 获取性别
	 * @return string
	 */
	public function getGender () {
		if ( ! $this->valid ) return "";
		if ( $this->sexNum % 2 == 0 ) {
			return "女";
		} else {
			return "男";
		}
	}

	/**
	 * 获取性别
	 * @return string
	 */
	public function getGenderEN () {
		if ( ! $this->valid ) return "";
		if ( $this->sexNum % 2 == 0 ) {
			return "female";
		} else {
			return "male";
		}
	}

	function __construct ( $identity = "" ) { // 定义构造函数
		$this->setIdentity ( $identity );
	}

	/**
	 * 设置身份证
	 * @param string $identity
	 */
	public function setIdentity ( $identity = "" ) {
		$this->identity = strtolower ( $identity );
		$this->valid = $this->checkIdentity ();
		if ( ! $this->valid ) {
			$this->areaNum = "";
			$this->dateNum = "";
			$this->sexNum = "";
		}
	}

	/**
	 * 验证性别
	 * @param number $sex 1为男，2为女，不输入为不验证
	 * @return boolean
	 */
	public function checkGender ( $sex = "" ) {
		if ( ! $this->valid ) return "";
		// 性别1为男，2为女
		if ( $sex == 1 ) {
			if ( isset ( $this->sexNum ) ) {
				if ( ! $this->checkSex ( $this->sexNum ) ) {
					return false;
				} else {
					return true;
				}
			}
		} else if ( $sex == 2 ) {
			if ( isset ( $this->sexNum ) ) {
				if ( $this->checkSex ( $this->sexNum ) ) {
					return false;
				} else {
					return true;
				}
			}
		} else {
			return "";
		}
	}
	
	// $num为身份证号码，$checkSex：1为男，2为女，不输入为不验证
	private function checkIdentity () {
		// 不是15位或不是18位都是无效身份证号
		if ( strlen ( $this->identity ) != 15 && strlen ( $this->identity ) != 18 ) {
			return false;
		}
		
		// 是数值
		if ( is_numeric ( $this->identity ) ) {
			// 如果是15位身份证号
			if ( strlen ( $this->identity ) == 15 ) {
				// 省市县（6位）
				$this->areaNum = substr ( $this->identity, 0, 6 );
				// 出生年月（6位）
				$this->dateNum = substr ( $this->identity, 6, 6 );
				// 性别（3位）
				$this->sexNum = substr ( $this->identity, 12, 3 );
			} else {
				// 如果是18位身份证号
				// 省市县（6位）
				$this->areaNum = substr ( $this->identity, 0, 6 );
				// 出生年月（8位）
				$this->dateNum = substr ( $this->identity, 6, 8 );
				// 性别（3位）
				$this->sexNum = substr ( $this->identity, 14, 3 );
				// 校验码（1位）
				$this->endNum = substr ( $this->identity, 17, 1 );
			}
		} else {
			// 不是数值
			if ( strlen ( $this->identity ) == 15 ) {
				return false;
			} else {
				// 验证前17位为数值，且18位为字符x
				$check17 = substr ( $this->identity, 0, 17 );
				if ( ! is_numeric ( $check17 ) ) {
					return false;
				}
				// 省市县（6位）
				$this->areaNum = substr ( $this->identity, 0, 6 );
				// 出生年月（8位）
				$this->dateNum = substr ( $this->identity, 6, 8 );
				// 性别（3位）
				$this->sexNum = substr ( $this->identity, 14, 3 );
				// 校验码（1位）
				$this->endNum = substr ( $this->identity, 17, 1 );
				if ( $this->endNum != 'x' && $this->endNum != 'X' ) {
					return false;
				}
			}
		}
		
		if ( isset ( $this->areaNum ) ) {
			if ( ! $this->checkArea () ) {
				return false;
			}
		}
		
		if ( isset ( $this->dateNum ) ) {
			if ( ! $this->checkDate () ) {
				return false;
			}
		}
		
		if ( isset ( $this->endNum ) ) {
			if ( ! $this->checkEnd ( $this->endNum, $this->identity ) ) {
				return false;
			}
		}
		return true;
	}
	
	// 验证城市
	private function checkArea () {
		$num1 = substr ( $this->areaNum, 0, 2 );
		$num2 = substr ( $this->areaNum, 2, 2 );
		$num3 = substr ( $this->areaNum, 4, 2 );
		// 根据GB/T2260—999，省市代码11到65
		if ( 10 < $num1 && $num1 < 66 ) {
			return true;
		} else {
			return false;
		}
		// ============
		// 对市 区进行验证
		// ============
		// 新的18位身份证号码各位的含义:
		// 1-2位省、自治区、直辖市代码； 11-65
		// 3-4位地级市、盟、自治州代码；
		// 5-6位县、县级市、区代码；
		// 7-14位出生年月日，比如19670401代表1967年4月1日；
		// 15-17位为顺序号，其中17位男为单数，女为双数；
		// 18位为校验码，0-9和X，由公式随机产生。
		// 举例：
		// 130503 19670401 0012这个身份证号的含义: 13为河北，05为邢台，03为桥西区，出生日期为1967年4月1日，顺序号为001，2为验证码。
		
		// 15位身份证号码各位的含义:
		// 1-2位省、自治区、直辖市代码；
		// 3-4位地级市、盟、自治州代码；
		// 5-6位县、县级市、区代码；
		// 7-12位出生年月日,比如670401代表1967年4月1日,这是和18位号码的第一个区别；
		// 13-15位为顺序号，其中15位男为单数，女为双数；
		// 与18位身份证号的第二个区别：没有最后一位的验证码。
		// 举例：
		// 130503 670401 001的含义; 13为河北，05为邢台，03为桥西区，出生日期为1967年4月1日，顺序号为001。
	}
	
	// 验证出生日期
	private function checkDate () {
		if ( strlen ( $this->dateNum ) == 6 ) {
			$date1 = substr ( $this->dateNum, 0, 2 );
			$date2 = substr ( $this->dateNum, 2, 2 );
			$date3 = substr ( $this->dateNum, 4, 2 );
			$statusY = $this->checkY ( '19' . $date1 );
		} else {
			$date1 = substr ( $this->dateNum, 0, 4 );
			$date2 = substr ( $this->dateNum, 4, 2 );
			$date3 = substr ( $this->dateNum, 6, 2 );
			$nowY = date ( "Y", time () );
			if ( 1900 < $date1 && $date1 <= $nowY ) {
				$statusY = $this->checkY ( $date1 );
			} else {
				return false;
			}
		}
		if ( 0 < $date2 && $date2 < 13 ) {
			if ( $date2 == 2 ) {
				// 润年
				if ( $statusY ) {
					if ( 0 < $date3 && $date3 <= 29 ) {
						return true;
					} else {
						return false;
					}
				} else {
					// 平年
					if ( 0 < $date3 && $date3 <= 28 ) {
						return true;
					} else {
						return false;
					}
				}
			} else {
				$maxDateNum = $this->getDateNum ( $date2 );
				if ( 0 < $date3 && $date3 <= $maxDateNum ) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
	
	// 验证性别
	private function checkSex () {
		if ( $this->sexNum % 2 == 0 ) {
			return false;
		} else {
			return true;
		}
	}
	
	// 验证18位身份证最后一位
	private function checkEnd ( $end, $num ) {
		$checkHou = array ( 1, 0, 'x', 9, 8, 7, 6, 5, 4, 3, 2 );
		$checkGu = array ( 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 );
		$sum = 0;
		for ( $i = 0; $i < 17; $i ++ ) {
			$sum += ( int ) $checkGu [ $i ] * ( int ) $num [ $i ];
		}
		$checkHouParameter = $sum % 11;
		if ( $checkHou [ $checkHouParameter ] != $num [ 17 ] ) {
			return false;
		} else {
			return true;
		}
	}
	
	// 验证平年润年，参数年份,返回 true为润年 false为平年
	private function checkY ( $Y ) {
		if ( getType ( $Y ) == 'string' ) {
			$Y = ( int ) $Y;
		}
		if ( $Y % 100 == 0 ) {
			if ( $Y % 400 == 0 ) {
				return true;
			} else {
				return false;
			}
		} else if ( $Y % 4 == 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	// 当月天数 参数月份（不包括2月） 返回天数
	private function getDateNum ( $month ) {
		if ( $month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12 ) {
			return 31;
		} else if ( $month == 2 ) {
		} else {
			return 30;
		}
	}

}