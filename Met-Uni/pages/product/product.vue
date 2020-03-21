<template>
	<view>
		<view class="goods-list m-t">
			<view 
				v-for="(item, index) in listData" :key="index"
				class="goods-item"
				@click="navToDetailPage(item)"
			>
				<view class="image-wrapper">
					<image :src="item.imgurl" mode="aspectFill"></image>
				</view>
				<text class="title">{{item.title}}</text>
			</view>
		</view>
		<uni-load-more :status="status"  :icon-size="16" :content-text="contentText" />
	</view>
</template>

<script>
	import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
	var api = require('@/common/api.js');
	var pagesize = 8;
	export default {
		components: {
			uniLoadMore	
		},
		data() {
			return {
				contentText: {
					contentdown: '上拉加载更多',
					contentrefresh: '加载中',
					contentnomore: '没有更多'
				},
				listData: [],
				page: 1,
				reload: false,
				status: 'more',
			};
		},
		
	onLoad() {
		this.loadData();
	},
	onPullDownRefresh() {
		this.reload = true;
		//this.page = 1;
		this.loadData();
	},
	onReachBottom() {
		this.status = 'more';
		this.loadData();
	},
		methods: {
			//加载商品 ，带下拉刷新和上滑加载
			async loadData() {
				var data = {};
				if (this.page) {
					//说明已有数据，目前处于上拉加载
					//this.status = 'loading';
					data.page = this.page;
					//data.time = new Date().getTime() + '';
					//data.pageSize = 10;
				}
				api.get({
					url: '?c=product&a=dolist',
					data: data,
					success: data => {
						
						if (data.data.length != 0) {
							let list = this.setTime(data.data);
							this.listData = this.reload ? list : this.listData.concat(list);
							this.page = this.page+1;
							this.reload = false;
						}
						else {
							this.reload = true;
							this.status = "no more";
						}
					},
					fail: (data, code) => {
						console.log('fail' + JSON.stringify(data));
					}
				});
			},
			setTime: function(items) {
				var newItems = [];
				items.forEach(e => {
					newItems.push({
						imgurl: api.HOST+'/'+e.imgurl,
						id: e.id,
						title: e.title
					});
				});
				return newItems;
			},
			//详情
			navToDetailPage(item){
				//测试数据没有写id，用title代替
				//let id = item.id;
				uni.navigateTo({
					url: "/pages/product/detail?detailopt="+encodeURIComponent(JSON.stringify(item))
				})
			}
		},
	}
</script>

<style lang="scss">
	page, .content{
		background: #ffffff;
	}
	.content{
		padding-top: 96upx;
	}

	/* 商品列表 */
	.goods-list{
		display:flex;
		flex-wrap:wrap;
		padding: 0 30upx;
		background: #fff;
		.goods-item{
			display:flex;
			flex-direction: column;
			width: 48%;
			padding-bottom: 40upx;
			&:nth-child(2n+1){
				margin-right: 4%;
			}
		}
		.image-wrapper{
			width: 100%;
			height: 330upx;
			border-radius: 3px;
			overflow: hidden;
			image{
				width: 100%;
				height: 100%;
				opacity: 1;
			}
		}
		.title{
			font-size: 32udpx;
			color: #000000;
			/*line-height: 80upx;*/
		}
		.price-box{
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-right: 10upx;
			font-size: 24upx;
			color: #666666;
		}
		.price{
			font-size: 16upx;
			color: $uni-color-primary;
			line-height: 1;
			&:before{
				content: '￥';
				font-size: 26upx;
			}
		}
	}
	

</style>
