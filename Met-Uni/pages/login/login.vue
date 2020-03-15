<template>
	<view>
		<!-- 登录框 -->
		<view class="loginBox">
			<view class="logoImgBox">
				<image class="logoImage" src="/static/logo.png"></image>
			</view>
			<view class="loginInptu">
				<input type="text" v-if="loginMode == 1" class="username" value="" placeholder="请输入用户名/手机号" maxlength="11" @input="phoneInput"/>
				<input type="number" v-else-if="loginMode == 2" class="username" value="" placeholder="请输入手机号" maxlength="11" @input="phoneInput"/>
			</view>
			<view class="loginInptu" v-if="loginMode == 1">
				<input type="password" class="password" value="" placeholder="请输入密码" maxlength="16" @input="passwordInpur"/>
			</view>
			<view class="loginInptu loginInptuCode" v-else-if="loginMode == 2">
				<input type="text" class="code" value="" placeholder="请输入验证码" maxlength="4" @input="codeInput"/>
				<view class="huoquyanzhengma" @click="getCode">{{codeBut}}</view>
			</view>
			<button class="cu-btn block bg-orange margin-tb-sm lg loginButton" @click="loginUp"> 登录</button>

		</view>
		<!-- 其他登录方式 -->
		<view class="otherLoginTitle">————————其他登录方式————————</view>
		<view class="otherLogin">
			<view class="weiixnLogin" @click="weixinlogin">
				<image class="otherIcon" src="/static/weixin.png" mode=""></image>
			</view>
			<view class="qqLogin" @click="qqlogin">
				<image class="otherIcon" src="/static/QQ.png" mode=""></image>
			</view>
			<view class="weiboLogin" @click="weibologin">
				<image class="otherIcon" src="/static/weibo.png" mode=""></image>
			</view>
		</view>

		<!-- 忘记密码/新用户注册 -->
		<view class="bottomBox">
			<view class="wangjimima">忘记密码</view>
			<view class="yonghuzhuce">用户注册</view>
		</view>
	</view>
</template>

<script>
	var api = require('@/common/api.js');
	export default {
		data() {
			return {
				loginMode: 1,
				usernameType:"text",
				codeBut:"获取验证码",
				loginImage:"/static/logo.png",
				codeClick:true,
				phone:"",
				password:"",
				code:"",
			}
		},
		onLoad() {
			this.signIn()
		},
		methods: {
			loginCode() {
				this.loginMode = 2
				this.usernameType = "number"
			},
			loginPhone() {
				this.loginMode = 1
				this.usernameType = "text"
			},
			// 手机号输入
			phoneInput(e){
				this.phone = e.detail.value
			},
			// 密码输入
			passwordInpur(e){
				this.password = e.detail.value
			},
			// 验证码输入
			codeInput(e){
				this.code = e.detail.value
			},
			// 点击登录
			loginUp(){
				var _that = this
				if(_that.phone == ""){
					uni.showToast({
						title: '请输入用户名',
						icon: 'none',
						duration:1000
					});
				}else if(_that.password == "" && _that.loginMode == 1){
					uni.showToast({
						title: '请输入密码',
						icon: 'none',
						duration:1000
					});
				}else if(_that.code == "" && _that.loginMode == 2){
					uni.showToast({
						title: '请输入验证码',
						icon: 'none',
						duration:1000
					});
				}else{
					//这里请求接口
					this.signIn();
					console.log(_that.phone,_that.password,_that.code)
				}
			},
			signIn(){
					api.post({
						url: '?c=member&a=dosignin',
						data: {
							username: this.phone,
							password: this.password
						},
						success: data => {
							if(data.code == 1){
								uni.switchTab({
									url: '../index/index'
								});

							} else {
								if(this.phone != '') {
									uni.showToast({
										title: '登录失败',
										icon: 'none',
										duration: 800
									});
								}
								return
							}
						}
					});					
			},
			
			// 获取验证码
			getCode(){
				var _that = this
				const mobsms = uni.requireNativePlugin('WB-MobSms');
				// 倒计时
				if(_that.codeClick){
					if(_that.phone[0] != 1 || _that.phone.length != 11){
						uni.showToast({
							title: '请输入正确的手机号',
							icon: 'none',
							duration: 800
						});
					}else{
						_that.codeClick = false
						// 发送短信
						mobsms.sendSms(
							{zone: '86',phone_number: _that.phone},
							result => {if (result.status == 'success') {
									uni.showToast({
										title: '验证码获取中',
										icon: 'loading',
										duration: 800
									});
								}
							}
						);
						// 倒计时开始
						var daojishi = 60
						var xianshi = setInterval(function(){
							_that.codeBut = "重新发送"+daojishi;
							daojishi--
							if(daojishi<=0){
								_that.codeBut = "重新发送"
								_that.codeClick = true
								clearInterval(xianshi);
							}
						},1000)
					}
				}
			},
			// 微信登录
			weixinlogin(){
				uni.login({
				  provider: 'weixin',
				  success: function (loginRes) {
				    console.log(loginRes.authResult);
				    // 获取用户信息
				    uni.getUserInfo({
				      provider: 'weixin',
				      success: function (infoRes) {
						console.log(infoRes)
				        console.log('用户昵称为：' + infoRes.userInfo.nickName);
				      }
				    });
				  }
				});
			},
			// qq登录
			qqlogin(){
				var _that = this
				uni.login({
				  provider: 'qq',
				  success: function (loginRes) {
					  uni.getUserInfo({
					    provider: 'qq',
					    success: function (infoRes) {
					  	console.log(infoRes)
							_that.loginImage = infoRes.userInfo.figureurl_qq_2
					    }
					  });
				  }
				});
			},
			// 微博登录
			weibologin(){
				uni.login({
					provider:"sinaweibo",
					success: function (loginRes) {
					  console.log(loginRes.authResult);
					}
				})
			}
		}
	}
</script>

<style>
	page {
		height: 100%;
	}

	.main {
		background: #FDE23D;
		width: 100%;
		height: 100%;
	}

	.mainHead {
		height: var(--status-bar-height);
	}

	.loginTop {
		width: 700upx;
		margin: 0 auto;
		height: 40upx;
		line-height: 40upx;
		display: flex;
		justify-content: space-between;
		font-size: 30upx;
		margin-top: 10upx;
	}

	.loginTopBack {
		width: 35upx;
		height: 35upx;
	}

	.loginUp {
		width: 100upx;
		height: 100upx;
		margin: 0 auto;
		padding: 15upx;
		border: 1upx solid #333333;
		border-radius: 60upx;
		margin-top: 60upx;
		background: #333333;
	}

	.code {
		background: url(../../static/code.png)no-repeat;
		background-position: 10upx;
		padding-left: 65upx;
		width: 300upx;
		background-size: 58upx;
	}

	.huoquyanzhengma {
		background: #f37b1d;
		color: #fff;
		line-height: 85upx;
		width: 200upx;
		text-align: center;
		border-radius: 10upx;
	}

	.loginBox {
		width: 650upx;
		margin: 60upx auto;
	}

	.username {
		background: url(../../static/shouji.png)no-repeat;
		background-position: 10upx;
		padding-left: 65upx;
	}

	.password {
		background: url(../../static/mima.png)no-repeat;
		background-position: 5upx;
		padding-left: 70upx;
	}

	.loginInptu {
		width: 500upx;
		height: 80upx;
		margin: 0 auto;
		background: #fff;
		margin-top: 20upx;
		line-height: 80upx;
		border-radius: 10upx;
	}

	.loginButton {
		width: 500upx;
		margin: 0 auto;
		margin-top: 30upx;
	}
	.loginInptuCode {
		display: flex;
	}

	.loginInptu>input {
		height: 100%;
		font-size: 30upx;
	}

	.logoImgBox {
		width: 200upx;
		height: 200upx;
		margin: 0 auto;
		margin-bottom: 50upx;
	}
	
	.logoImage {
		width: 120upx;
		height: 120upx;
		margin-top: 40upx;
	}

	.otherLogin {
		width: 500upx;
		height: 95upx;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
	}
	
	.otherIcon {
		width: 95upx;
		height: 95upx;
	}

	.otherLogin>view {
		width: 95upx;
		height: 95upx;
		background: #fff;
		border-radius: 95upx;
	}

	.otherLoginTitle {
		width: 100%;
		text-align: center;
		color: #555555;
		font-size: 28upx;
		line-height: 80upx;
	}

	.bottomBox {
		display: flex;
		justify-content: center;
		position: fixed;
		bottom: 60upx;
		left: 0;
		right: 0;
		margin: auto;
		height: 30upx;
		line-height: 30upx;
		font-size: 32upx;
	}

	.wangjimima {
		border-right: 1rpx solid #555;
		height: 25upx;
		padding-right: 15upx;
	}

	.yonghuzhuce {
		padding-left: 15upx;
	}
</style>
