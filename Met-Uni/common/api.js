//判断登陆设备
//#ifdef H5
var device_type = 'web'
//#endif

//#ifdef APP-PLUS
var device_type = 'mobile'
//#endif

//#ifdef MP
var device_type = 'wxapp'
//#endif

var app = getApp();

var host = "http://localhost:8026";  //替换为自己的域名,小程序需要https
var socketUrl = "wss://hrai.online:39005/"; //替换为自己的websocket地址,小程序需要wss

module.exports = {
	socketUrl:socketUrl,
	HOST: host,
	API_ROOT: host + '/api/',
	DeviceType: device_type,
	post(options) {
		this.request(options);
	},
	delete(options) {
		options.method = 'DELETE';
		this.request(options);
	},
	get(options) {
		options.method = 'GET';
		this.request(options);
	},
	put(options) {
		options.method = 'PUT';
		this.request(options);
	},
	request(options) {
		var apiRoot = this.API_ROOT;
		var token = '';
		try {
			token = uni.getStorageSync('token')
		} catch (e) {
			// Do something when catch error
		}

		uni.request({
			url: apiRoot + options.url,
			data: options.data,
			method: options.method ? options.method : 'POST',
			header: {
				'Content-Type': 'application/x-www-form-urlencoded',
				'XX-Token': token,
				'XX-Device-Type': device_type,
			},
			success: res => {
				var data = res.data;
				if (data.code == 10001) {
					uni.showModal({
						title: '提示',
						content: '登录已过期，请重新登录',
						success: (res) => {
							if (res.confirm) {
								uni.navigateTo({
									url: '../login/login'
								});
							}
						}
					});
				} else {
					options.success(data);
				}
			},
			fail: function(res) {
				if (options.fail) {
					options.fail(res)
				}
			},
			complete: options.complete ? options.complete : null
		});
	},
	uploadFile(options) {

		options.url = this.API_ROOT + options.url;

		let token = this.getToken();

		let that = this;

		let oldSuccess = options.success;
		options.success = function(res) {
			//console.log(res.data);
			let data = JSON.parse(res.data);
			if (data.code == 0 && data.data && data.data.code && data.data.code == 10001) {
				// uni.navigateTo({
				//     url: '/pages/login/login'
				// });
				that.login();
			} else {
				oldSuccess(data);
			}
		}

		options.header = {
			'XX-Token': token,
			'XX-Device-Type': device_type
		};
		uni.uploadFile(options);

	},
	getToken() {
		var token = '';
		try {
			token = uni.getStorageSync('token')
		} catch (e) {
			// Do something when catch error
		}

		return token;
	}
};
