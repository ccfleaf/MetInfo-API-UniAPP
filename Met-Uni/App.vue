<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style>
	/*每个页面公共css */
    @import "colorui/main.css";
    @import "colorui/icon.css";	
	@import "common/uni.css";
	
	page {
		background: #f5f5f5;
	}
	.m-t{
		margin-top: 16upx;
	}
	
	.tit-icon{
		flex-shrink: 0;
		width: 64upx;
		height: 64upx;
		margin-left: 10px;
	}
	
	.tit-bar{
		left:20upx;
		position: absolute;
	}
	
</style>
